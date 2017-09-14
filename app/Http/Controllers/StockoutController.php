<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Customer;
use App\Import;
use App\Product;
use App\Purchaseorder;
use App\Stockout;
use App\Subimport;
use Carbon\Carbon;
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
            $Brand_id = $purchase->user->brand_id;//Get brand_id by user ID
            $product = $purchase->products()->get();// Get all product by Invoice/Purchase Order

            //Insert record to table substock if that user is SD
            if($Brand_id!=0 && $Brand_id!=null){
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
            $stockout_id = $stockout->id;

            foreach ($product as $p) {
                $qtyIn = $p->pivot->qty;
                $product_id = $p->id;
                $qt=$qtyIn;

                $brand_product_qty = 0;
                $brand_product_productId = 0;
                $brand_product_brandId = 0;
                if($Brand_id!=0 && $Brand_id!=null) {
                    $Brand = DB::table('brand_product')->where([['brand_id', '=', $Brand_id], ['product_id', '=', $product_id],])->get();
                    foreach ($Brand as $B) {
                        $brand_product_qty = $B->qty;
                        $brand_product_productId = $B->product_id;
                        $brand_product_brandId = $B->brand_id;
                    }
//
                }

                $result = DB::select("SELECT id, qty, importId FROM `import_product` WHERE productId = {$product_id} AND qty > 0 "); // Select all product in import
                foreach ($result as $r){
                    $res = DB::select("SELECT id, qty, importId,productId, mfd, expd FROM `import_product` WHERE importId = {$r->importId} AND productId={$product_id} AND qty > 0");//select one by one from import
                    foreach ($res as $s){
                        $qt=$qt;
                        if( $a>=$qtyIn | $s->qty >= $qtyIn){
                            if($j!=$product_id){
                                $m = $s->qty - $qt;
                                if($m >=0){
                                    DB::table('import_product')->where('id', $s->id)->update(array('qty'=>$m));
                                    $prod = Product::findOrFail($product_id);//Update qty to main table product
                                    $bqty = $prod->qty;
                                    $uqty = ($bqty-$qtyIn);
                                    $prod->qty = $uqty;
                                    $prod->save();

                                    $Up = Purchaseorder::findOrFail($invoiceN);//Update table purchaseorder in field isDelivery to 1
                                    $Up->isDelivery=1;
                                    $Up->save();

                                    if($Brand_id!=0 && $Brand_id!=null) {//Insert data to substock if that user is SD
                                        DB::table('subimport_product')->insert(['subimport_id' => $subimport_id, 'product_id' => $product_id, 'qty' => $qt, 'mfd' => $s->mfd, 'expd' =>$s->expd]);

                                        if($brand_product_productId!=0 && $brand_product_brandId!=0){
                                            $qtyUpdate_brand_product = $brand_product_qty+$qtyIn;
                                            DB::table('brand_product')->where([['brand_id', '=', $brand_product_brandId], ['product_id', '=', $brand_product_productId],])->update(array('qty'=>$qtyUpdate_brand_product));
                                            $brand_product_productId=0;$brand_product_brandId=0;
                                        }else{
                                            DB::table('brand_product')->insert(['brand_id' => $Brand_id, 'product_id' => $product_id, 'qty' => $qtyIn]);
                                        }
                                    }
                                    DB::table('import_stockout')->insert(['stockout_id'=>$stockout_id,'import_id'=>$s->importId, 'product_id' => $product_id, 'qty' => $qt,'expd' =>$s->expd]);
                                    $j = $product_id;
                                    $a=0;
                                }
                            }
                        }
                        elseif($s->qty < $qtyIn || $a < $qtyIn)
                        {
                            if($j!=$product_id)
                            {
                                $a = $a + $s->qty;
                                if ($a >= $qtyIn)
                                {
                                    $m = $s->qty - $qt;
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
                                    if($Brand_id!=0 && $Brand_id!=null)
                                    {//Insert data to substock if that user is SD
                                        DB::table('subimport_product')->insert(['subimport_id' => $subimport_id, 'product_id' => $product_id, 'qty' => $qt, 'mfd' => $s->mfd, 'expd' =>$s->expd]);

                                        if($brand_product_productId!=0 && $brand_product_brandId!=0)
                                        {
                                            $qtyUpdate_brand_product = $brand_product_qty+$qtyIn;
                                            DB::table('brand_product')->where([['brand_id', '=', $brand_product_brandId], ['product_id', '=', $brand_product_productId],])->update(array('qty'=>$qtyUpdate_brand_product));
                                            $brand_product_productId=0;$brand_product_brandId=0;
                                        }
                                        else
                                        {
                                            DB::table('brand_product')->insert(['brand_id' => $Brand_id, 'product_id' => $product_id, 'qty' => $qtyIn]);
                                        }
                                    }
                                    DB::table('import_stockout')->insert(['stockout_id'=>$stockout_id,'import_id'=>$s->importId, 'product_id'=>$product_id, 'qty'=>$qt, 'expd'=>$s->expd]);
                                    $j = $product_id;
                                    $a = 0;
                                }
                                elseif ($a < $qtyIn)
                                {
                                    $m = $qt - $s->qty;
                                    $qt = $m;
                                    DB::table('import_product')->where('id', $s->id)->update(array('qty'=>0));
                                    if($Brand_id!=0 && $Brand_id!=null)
                                    {//Insert data to substock if that user is SD
                                        DB::table('subimport_product')->insert(['subimport_id' => $subimport_id, 'product_id' => $product_id, 'qty' => $s->qty, 'mfd' => $s->mfd, 'expd' => $s->expd]);
                                    }
                                    DB::table('import_stockout')->insert(['stockout_id'=>$stockout_id,'import_id'=>$s->importId, 'product_id' => $product_id, 'qty' => $s->qty, 'expd' => $s->expd]);
                                }
                            }
                        }
                    }


                }
            }
        }
        return redirect()->route('stockout.index');
//
    }//stock out and sub-stock



    public function show($id)
    {
        //views detail of stock invoice
        $user_name="";
        $purchaseOrder = Purchaseorder::findOrFail($id);
        $customer_id = $purchaseOrder->customer_id;
        if($customer_id){
            $user_name =$purchaseOrder->customer->name;
        }else{
            $contact = $purchaseOrder->user->contactNum;
            $user_name= Customer::where('contactNo','=',$contact)->value('name');
        }
        return view('admin/stock_out/views',compact('purchaseOrder','user_name'));
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
        $user_name="";
        $Purchase = Purchaseorder::findOrFail($id);
        $customer_id = $Purchase->customer_id;
        if($customer_id){
            $user_name =$Purchase->customer->name;
        }else{
            $contact = $Purchase->user->contactNum;
            $user_name= Customer::where('contactNo','=',$contact)->value('name');
        }
        $date=$Purchase->invoiceDate;
        return response()->json(['userName'=>$user_name,'invoiceDate'=>$date]);
    }
}
