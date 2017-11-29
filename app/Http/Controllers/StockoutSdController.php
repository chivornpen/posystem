<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Purchaseordersd;
use Auth;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Stockoutsd;
use App\Customer;
class StockoutSdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brandId = Auth::user()->brand->id;
        $stockout = Stockoutsd::where('brand_id','=',$brandId)->get();
        return view('admin.stock_out_sd.index',compact('stockout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $invoices= array();
        if(Auth::user()->position->name == 'SD'){
            $brandid = Auth::user()->brand->id;
            $userids = User::where('brand_id','=',$brandid)->get();
            foreach ($userids as $userid) {
                $invoices=array(Purchaseordersd::where('user_id','=',$userid->id)->where('isDelivery','=',0)->get());
            }
           return view('admin.stock_out_sd.create',compact('invoices'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //-----cut sub stock------------
        $m = ""; $j=0; $qt=0; $a=0;
            $brandId = Auth::user()->brand->id;
            $invoice = Input::get('invoiceN');
            $stockoutDate = Input::get('date');
            $add1year = Carbon::now()->addYear(1)->toDateString();
            $purchase = Purchaseordersd::findOrFail($invoice);
            $products = $purchase->products()->get();// Get all product by Invoice/Purchase Order

            $stockoutsd = new Stockoutsd();//Insert to table stockout
            $stockoutsd->stockoutDate = $stockoutDate;
            $stockoutsd->purchaseordersd_id =$invoice;
            $stockoutsd->user_id = Auth::user()->id;
            $stockoutsd->brand_id = $brandId;
            $stockoutsd->save();
            $stockoutsd_id = $stockoutsd->id;

            foreach ($products as $product) {
                $qtyIn = $product->pivot->qty;
                $product_id = $product->id;
                $qt = $qtyIn;
                
                $result = DB::table('subimport_product')->where([['product_id','=',$product_id],['brand_id','=',$brandId],['qty','>',0],])->select('id', 'qty','subimport_id')->get();
                 // Select all product in import
                foreach ($result as $r){
                    $res = DB::table('subimport_product')->select('id', 'qty', 'subimport_id','product_id', 'mfd', 'expd')->where([['subimport_id','=',$r->subimport_id],['product_id','=',$product_id],['brand_id','=',$brandId],['qty','>',0],['expd','>',$add1year],])->get();//select one by one from import
                    //dd($res);
                    if($res){
                        foreach ($res as $s) {
                            $qt = $qt;
                            if ($a >= $qtyIn | $s->qty >= $qtyIn) {
                                if ($j != $product_id) {
                                    $m = $s->qty - $qt;
                                    if ($m >= 0) {
                                        DB::table('subimport_product')->where('id', $s->id)->update(array('qty' => $m));
                                        $bqtys = DB::select("SELECT qty FROM `brand_product` WHERE product_id = {$product_id} AND brand_id = {$brandId}");
                                         $baseqty="";
                                        foreach ($bqtys as $bqty) {
                                           $baseqty=$bqty->qty;
                                        }
                                        $uqty = ($baseqty - $qtyIn);
                                        DB::table('brand_product')->where('brand_id','=',$brandId)->where('product_id', $product_id)->update(array('qty' => $uqty));
                                        $Up = Purchaseordersd::findOrFail($invoice);//Update table purchaseorder in field isDelivery to 1
                                        $Up->isDelivery = 1;
                                        $Up->save();
                                        DB::table('import_stockoutsd')->insert(['stockoutsd_id' => $stockoutsd_id, 'subimport_id' => $s->subimport_id, 'product_id' => $product_id, 'qty' => $qt, 'expd' => $s->expd]);
                                        $j = $product_id;
                                            $a = 0;
                                    }
                                }
                            }elseif($s->qty < $qtyIn || $a < $qtyIn){
                                if ($j != $product_id) {
                                    $a = $a + $s->qty;
                                    if ($a >= $qtyIn) {
                                        $m = $s->qty - $qt;
                                        DB::table('subimport_product')->where('id', $s->id)->update(array('qty' => $m));
                                        $bqtys = DB::select("SELECT qty FROM `brand_product` WHERE product_id = {$product_id} AND brand_id = {$brandId}");//select qty from brand product
                                        $baseqty="";
                                        foreach ($bqtys as $bqty) {
                                           $baseqty=$bqty->qty;
                                        }

                                        $uqty = ($baseqty - $qtyIn);
                                        DB::table('brand_product')->where('brand_id','=',$brandId)->where('product_id', $product_id)->update(array('qty' => $uqty));
                                        $Up = Purchaseordersd::findOrFail($invoice);//Update field delivery to 1
                                        $Up->isDelivery = 1;
                                        $Up->save();
                                        //insert record to table subimport / into substock detail
                                        DB::table('import_stockoutsd')->insert(['stockoutsd_id' => $stockoutsd_id, 'subimport_id' => $s->subimport_id, 'product_id' => $product_id, 'qty' => $qt, 'expd' => $s->expd]);
                                        $j = $product_id;
                                        $a = 0;
                                    }elseif ($a < $qtyIn) {
                                        $m = $qt - $s->qty;
                                        $qt = $m;
                                            DB::table('subimport_product')->where('id', $s->id)->update(array('qty' => 0));
                                            DB::table('import_stockoutsd')->insert(['stockoutsd_id' => $stockoutsd_id, 'subimport_id' => $s->subimport_id, 'product_id' => $product_id, 'qty' => $s->qty, 'expd' => $s->expd]);
                                    }
                                }
                            } 
                        }
                    }
                }
            }
            //------------------------------
            return redirect()->route('stockoutsd.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getIdPoSd($id)
    {
        $Purchase = Purchaseordersd::findOrFail($id);
        $user_name = $Purchase->customer->name;
        $date= $Purchase->poDate;
        return response()->json(['userName'=>$user_name,'invoiceDate'=>$date]);
    }
    public function viewDetailStockoutSd($id)
    {
        //views detail of stock invoice
        $purchaseOrder = Purchaseordersd::findOrFail($id);
        return view('admin/stock_out_sd/views',compact('purchaseOrder'));
    }
}