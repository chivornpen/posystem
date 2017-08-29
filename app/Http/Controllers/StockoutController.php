<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Import;
use App\Product;
use App\Purchaseorder;
use App\Stockout;
use App\Subimport;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockoutController extends Controller
{

    public function index()
    {
        $stockout = Stockout::all();
        return view('admin.stock_out.index',compact('stockout'));
    }


    public function create()
    {

       $invoice = Purchaseorder::all()->where('isDelivery','=',0)->where('isGenerate','=', 1);
//       dd($invoice);
       return view('admin.stock_out.create',compact('invoice'));
    }

    public function store(Request $re)
    {
        $m = ""; $j=0; $qt=0; $a=0;
        $this->validate($re,[
            'invoiceN'=>'required',
            'date'=>'required'
        ]);
        $stockoutDate = $re->date;
        $user_id = Auth::user()->id;
        $invoiceN = $re->input('invoiceN');//Get Invoice number from combobox in Form
        if($invoiceN!=0){
            $purchase = Purchaseorder::findOrFail($invoiceN);//Find purchase by In that got from comboBox from form
            $SD = $purchase->customer->channel->name;
            $Brand_id = $purchase->user->brand_id;//Get brand_id by user ID
            $product = $purchase->products()->get();// Get all product by Invoice/Purchase Order

            //Insert record to table substock if that user is SD
            if(strtolower($SD)=="sd"){
                $substock = new Subimport();
                $substock->subimportDate=$stockoutDate;
                $substock->purchaseorder_id=$invoiceN;
                $substock->brand_id="$Brand_id";
                $substock->supplier_id=1;
                $substock->imported_by=$user_id;
                $substock->save();
                $subimport_id = $substock->id;
            }
            $stockout = new Stockout();//Insert to table stockout
            $stockout->stockoutDate =$stockoutDate;
            $stockout->purchaseorder_id =$invoiceN;
            $stockout->user_id = $user_id;
            $stockout->save();

            foreach ($product as $p) {
                $qtyIn = $p->pivot->qty;
                $product_id = $p->id;
                $qt=$qtyIn;
                $brand_product_qty = 0;
                $brand_product_productId = 0;
                $brand_product_brandId = 0;
                if(strtolower($SD)=="sd") {
                    $Brand = DB::table('brand_product')->where([['brand_id', '=', $Brand_id], ['product_id', '=', $product_id],])->get();
//                    print_r($Brand);
                    foreach ($Brand as $B) {
                        $brand_product_qty = $B->qty;
                        $brand_product_productId = $B->product_id;
                        $brand_product_brandId = $B->brand_id;
                    }
//                    echo "<br>QTY = ".$brand_product_qty."<br>";
//                    echo "Product ID = ".$brand_product_productId."<br>";
//                    echo "Brand ID = ".$brand_product_brandId."<br>";
                }

//                echo "<br> Product ID ".$product_id."=\tQuantities ".$qtyIn."<br>";
                $result = DB::select("SELECT id, qty, importId FROM `import_product` WHERE productId = {$product_id} AND qty > 0 "); // Select all product in import
                foreach ($result as $r){
//                    echo " Import ID :".$r->importId." Product ID : ".$product_id." Quantities : ". $r->qty."<br>";
                    $res = DB::select("SELECT id, qty, importId, mfd, expd FROM `import_product` WHERE importId = {$r->importId} AND productId={$product_id} AND qty > 0");//select one by one from import
                    foreach ($res as $s){
                            $qt=$qt;
                            if( $a>=$qtyIn | $s->qty >= $qtyIn){
                                if($j!=$product_id){
//                                    echo "$s->qty >= $qtyIn"."<br>";
                                    $m = $s->qty - $qt;
                                    if($m >=0){
//                                        echo "ID".$s->id."<br>";
//                                        echo $m."<br>"; //here we update record to database
//                                        echo "Update to database..in block a"."<br><br>";
                                        DB::table('import_product')->where('id', $s->id)->update(array('qty'=>$m));

                                        $prod = Product::findOrFail($product_id);//Update qty to main table product
                                        $bqty = $prod->qty;
                                        $uqty = ($bqty-$qtyIn);
                                        $prod->qty = $uqty;
                                        $prod->save();

                                        $Up = Purchaseorder::findOrFail($invoiceN);//Update table purchaseorder in field isDelivery to 1
                                        $Up->isDelivery=1;
                                        $Up->save();

                                        if(strtolower($SD)=="sd") {//Insert data to substock if that user is SD
                                            DB::table('subimport_product')->insert(['subimport_id' => $subimport_id, 'product_id' => $product_id, 'qty' => $qt, 'mfd' => $s->mfd, 'expd' => $s->expd]);
                                            if($brand_product_productId!=0 && $brand_product_brandId!=0){
                                                $qtyUpdate_brand_product = $brand_product_qty+$qtyIn;
                                                DB::table('brand_product')->where([['brand_id', '=', $brand_product_brandId], ['product_id', '=', $brand_product_productId],])->update(array('qty'=>$qtyUpdate_brand_product));
                                                $brand_product_productId=0;$brand_product_brandId=0;
                                            }else{
                                                DB::table('brand_product')->insert(['brand_id' => $Brand_id, 'product_id' => $product_id, 'qty' => $qtyIn]);
                                            }
                                        }
//                                        echo "Base Quantities = ".$bqty."<br>";
//                                        echo "Order Quantities = ".$qtyIn."<br>";
//                                        echo "Update Quantities = ".$uqty=($bqty-$qtyIn)."<br>";
                                        $j = $product_id;
                                        $a=0;
                                    }
                                }
                            }elseif($s->qty < $qtyIn || $a < $qtyIn){

                                if($j!=$product_id) {
                                    $a = $a + $s->qty;
//                                    echo "a = ".$a."and s->qty :".$s->qty."<br>";
                                    if ($a >= $qtyIn) {
                                       $m = $s->qty - $qt;
//                                        echo "ID".$s->id."<br>";
//                                        echo $m."<br>"; //Update to product  sfsdf
                                        DB::table('import_product')->where('id', $s->id)->update(array('qty'=>$m));
                                        $prod = Product::findOrFail($product_id);
                                        $bqty = $prod->qty;
                                        $uqty = ($bqty-$qtyIn);
                                        $prod ->qty = $uqty;
                                        $prod->save();

                                        $Up = Purchaseorder::findOrFail($invoiceN);//Update field delivery to 1
                                        $Up->isDelivery=1;
                                        $Up->save();

                                        //insert record to table subimport / into substock detail
                                        if(strtolower($SD)=="sd") {//Insert data to substock if that user is SD
                                            DB::table('subimport_product')->insert(['subimport_id' => $subimport_id, 'product_id' => $product_id, 'qty' => $qt, 'mfd' => $s->mfd, 'expd' => $s->expd]);
                                            if($brand_product_productId!=0 && $brand_product_brandId!=0){
                                                $qtyUpdate_brand_product = $brand_product_qty+$qtyIn;
                                                DB::table('brand_product')->where([['brand_id', '=', $brand_product_brandId], ['product_id', '=', $brand_product_productId],])->update(array('qty'=>$qtyUpdate_brand_product));
                                                $brand_product_productId=0;$brand_product_brandId=0;
                                            }else{
                                                DB::table('brand_product')->insert(['brand_id' => $Brand_id, 'product_id' => $product_id, 'qty' => $qtyIn]);
                                            }
                                        }
//                                        echo "Base Quantities = ".$bqty."<br>";
//                                        echo "Order Quantities = ".$qtyIn."<br>";
//                                        echo "Update Quantities = ".$uqty=($bqty-$qtyIn)."<br>";
//                                        echo "Update to database...."."<br><br>";
                                        $j = $product_id;
                                        $a = 0;
                                    } elseif ($a < $qtyIn) {
                                        $m = $qt - $s->qty;
                                        $qt = $m;
//                                        echo "ID".$s->id."<br>";
//                                        echo $m."<br>";//Update always 0
//                                        echo "Values = 0<br><br>";
                                        DB::table('import_product')->where('id', $s->id)->update(array('qty'=>0));

                                        if(strtolower($SD)=="sd") {//Insert data to substock if that user is SD
                                            DB::table('subimport_product')->insert(['subimport_id' => $subimport_id, 'product_id' => $product_id, 'qty' => $s->qty, 'mfd' => $s->mfd, 'expd' => $s->expd]);
                                        }

                                    }
                                }
                            }
                    }
                }
            }
        }
        return redirect()->route('stockout.index');

    }//stock out and sub-stock


    public function show($id)
    {
        //views detail of stock invoice
        $purchaseOrder = Purchaseorder::findOrFail($id);
        return view('admin/stock_out/views',compact('purchaseOrder'));
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    //this function get from ajax to change customer name and invoice date
    public  function InvNChange($id){

        $Purchase = Purchaseorder::findOrFail($id);

        $user_name =$Purchase->customer->name;
        $date=$Purchase->invoiceDate;
        return response()->json(['userName'=>$user_name,'invoiceDate'=>$date]);
    }
}
