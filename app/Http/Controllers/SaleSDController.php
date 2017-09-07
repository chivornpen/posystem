<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use Auth;
use App\Brand;
use App\TpmPurchaseOrder;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Channel;
use App\Purchaseordersd;
use App\Tmppurchaseordercussd;
use App\Tmpeditpurchaseordercussd;
use App\Product;
use App\User;

class SaleSDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {     $sale= array();
        
        if(Auth::user()->position->name == 'SD'){
            $brandid = Auth::user()->brand->id;
            $userids = User::where('brand_id','=',$brandid)->get();
            foreach ($userids as $userid) {
                $sale=array(Purchaseordersd::where('user_id','=',$userid->id)->get());
            }
            return view('admin.saleSD.index',compact('sale'));
        }else{
            $sale = Purchaseordersd::all();
            return view('admin.saleSD.index1',compact('sale'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brandid = Auth::user()->brand->id;
        $customers = Customer::where('brand_id','=',$brandid)->get();
        $products = Brand::findOrFail($brandid)->products()->get();
        return view('admin.saleSD.create',compact('customers','products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $m = ""; $j=0; $qt=0; $a=0;
         if(Input::get('btn_yes')){
            $po = new Purchaseordersd;
            $po->poDate = Carbon::now();
            $po->dueDate = Input::get('dueDate');
            $po->customer_id = Input::get('cus');
            $po->totalAmount = Input::get('total');
            $po->grandTotal = Input::get('grandTotal');
            if(Input::get('discount')!=null){
                $po->discount = Input::get('discount');
            }else{
                $po->discount = 0;
            }            
            $po->user_id = Auth::user()->id;
            $po->isGenerate =0;
            $po->isDelivery =1;
            $po->cod = Input::get('cod');
            $po->save();
            $tmps = Tmppurchaseordercussd::where('user_id','=',Auth::user()->id)->get();
                foreach ($tmps as $tmp) {
                $po->products()->attach($tmp->product_id,
                    ['unitPrice'=>$tmp->unitPrice,
                    'qty'=>$tmp->qty,
                    'amount'=>$tmp->amount,
                    'user_id'=>$tmp->user_id]);
                }
            $tmps = Tmppurchaseordercussd::where('user_id','=',Auth::user()->id)->get();
            foreach ($tmps as $tmp) {
                $tmp->delete();
            }
            //-----cut sub stock------------
            $invoice = $po->id;
            $add1year = Carbon::now()->addYear(1)->toDateString();
            $purchase = Purchaseordersd::findOrFail($invoice);
            $products = $purchase->products()->get();// Get all product by Invoice/Purchase Order

            $stockout = new Stockoutsd();//Insert to table stockout
            $stockout->stockoutDate = Carbon::now();
            $stockout->purchaseordersd_id =$invoice;
            $stockout->user_id = $user_id;
            $stockout->save();
            $stockout_id = $stockout->id;

            foreach ($products as $product) {
                $qtyIn = $product->pivot->qty;
                $product_id = $product->id;
                $qt = $qtyIn;
                $result = DB::select("SELECT id, qty, subimport_id FROM `subimport_product` WHERE product_id = {$product_id} AND qty > 0 "); // Select all product in import
                foreach ($result as $r){
                    $res = DB::table('subimport_product')->select('id', 'qty', 'import_id','product_id', 'mfd', 'expd')->where([['subimport_id','=',$r->subimport_id],['product_id','=',$product_id],['qty','>',0],['expd','>',$add1year],])->get();//select one by one from import
                    if($res){
                        foreach ($res as $s) {
                            $qt = $qt;
                            if ($a >= $qtyIn | $s->qty >= $qtyIn) {
                                if ($j != $product_id) {
                                    $m = $s->qty - $qt;
                                    if ($m >= 0) {
                                        DB::table('subimport_product')->where('id', $s->id)->update(array('qty' => $m));
                                        $brandId = Auth::user()->brand->id;
                                        $bqty = DB::select("SELECT qty FROM `brand_product` WHERE product_id = {$product_id} AND brand_id = {$brandId}");
                                        $uqty = ($bqty - $qtyIn);
                                        DB::table('brand_product')->where('id', $s->id)->where('brand_id','=',$brandId)->update(array('qty' => $m));
                                        $Up = Purchaseordersd::findOrFail($invoiceN);//Update table purchaseorder in field isDelivery to 1
                                        $Up->isDelivery = 1;
                                        $Up->save();
                                        DB::table('import_stockoutsd')->insert(['stockout_id' => $stockout_id, 'import_id' => $s->import_id, 'product_id' => $product_id, 'qty' => $qt, 'expd' => $s->expd]);
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
                                        $brandId = Auth::user()->brand->id;
                                        $bqty = DB::select("SELECT qty FROM `brand_product` WHERE product_id = {$product_id} AND brand_id = {$brandId}");
                                        $uqty = ($bqty - $qtyIn);
                                        DB::table('brand_product')->where('id', $s->id)->where('brand_id','=',$brandId)->update(array('qty' => $m));
                                        $Up = Purchaseorder::findOrFail($invoiceN);//Update field delivery to 1
                                        $Up->isDelivery = 1;
                                        $Up->save();
                                        //insert record to table subimport / into substock detail
                                        DB::table('import_stockoutsd')->insert(['stockout_id' => $stockout_id, 'import_id' => $s->import_id, 'product_id' => $product_id, 'qty' => $qt, 'expd' => $s->expd]);
                                        $j = $product_id;
                                        $a = 0;
                                    }
                                }
                            } elseif ($a < $qtyIn) {
                                $m = $qt - $s->qty;
                                $qt = $m;
                                    DB::table('subimport_product')->where('id', $s->id)->update(array('qty' => 0));
                                    DB::table('import_stockoutsd')->insert(['stockout_id' => $stockout_id, 'import_id' => $s->import_id, 'product_id' => $product_id, 'qty' => $s->qty, 'expd' => $s->expd]);
                            }
                        }
                    }
                }
            }
            //------------------------------
            return redirect()->route('saleSD.index');
        }
        //------------------btn_no----------------
        if(Input::get('btn_no')){
            $po = new Purchaseordersd;
            $po->poDate = Carbon::now();
            $po->dueDate = Input::get('dueDate');
            $po->customer_id = Input::get('cus');
            $po->totalAmount = Input::get('total');
            $po->grandTotal = Input::get('grandTotal');
            if(Input::get('discount')!=null){
                $po->discount = Input::get('discount');
            }else{
                $po->discount = 0;
            }            
            $po->user_id = Auth::user()->id;
            $po->isGenerate =0;
            $po->isDelivery =0;
            $po->cod = Input::get('cod');
            $po->save();
            $tmps = Tmppurchaseordercussd::where('user_id','=',Auth::user()->id)->get();
                foreach ($tmps as $tmp) {
                $po->products()->attach($tmp->product_id,
                    ['unitPrice'=>$tmp->unitPrice,
                    'qty'=>$tmp->qty,
                    'amount'=>$tmp->amount,
                    'user_id'=>$tmp->user_id]);
                }
            $tmps = Tmppurchaseordercussd::where('user_id','=',Auth::user()->id)->get();
            foreach ($tmps as $tmp) {
                $tmp->delete();
            }
            return redirect()->route('saleSD.index');
        }
        //------------------btn_back---------------
        if(Input::get('btn_back')){
            $tmps = Tmppurchaseordercussd::where('user_id','=',Auth::user()->id)->get();
            foreach ($tmps as $tmp) {
                $tmp->delete();
            }
            return redirect()->back();
        }
        //------------------btn_cancel--------------------------
        if(Input::get('btn_cancel')){
            $tmps = Tmppurchaseordercussd::where('user_id','=',Auth::user()->id)->get();
            foreach ($tmps as $tmp) {
                $tmp->delete();
            }
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $details = Purchaseordersd::findOrFail($id);
        return view('admin.saleSD.showPoDetails',compact('details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tmps = Tmpeditpurchaseordercussd::where('purchaseorder_id','=',$id)->get();
            foreach ($tmps as $tmp) {
                $tmp->delete();
            }
        $pos=Purchaseordersd::findOrFail($id);
        $product = Purchaseordersd::findOrFail($id)->products()->get();
         foreach ($product as $po) {
            Tmpeditpurchaseordercussd::create([
                'purchaseorder_id'=>$pos->id,
                'product_id'=>$po->pivot->product_id,
                'qty'=>$po->pivot->qty,
                'unitPrice'=>$po->pivot->unitPrice,
                'amount'=>$po->pivot->amount,
                'recordStatus'=>'n',
                'user_id'=>Auth::user()->id
            ]);
         }
        $brandid = Auth::user()->brand->id;
        $products = Brand::findOrFail($brandid)->products()->get();
        $potmps = Tmpeditpurchaseordercussd::where('purchaseorder_id','=',$id)->get();
         return view('admin.saleSD.edit',compact('pos','potmps','products'));
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
        //dd('test');
        if(Input::get('btn_add')){
            $tmpedit = new Tmpeditpurchaseordercussd;
            $poid = Input::get('poid');
            $proid = Input::get('product_id');
            $qty = Tmpeditpurchaseordercussd::where('purchaseorder_id','=',$poid)->where('product_id','=',$proid)->where('recordStatus','!=','r')->value('qty');
            if($qty!=null){
                DB::statement("DELETE FROM tmpeditpurchaseordercussd WHERE purchaseorder_id={$poid} AND product_id={$proid}");
                $newQtys = Input::get('qty');
                $newQty = (int)$newQtys;
                $qtylast = $qty + $newQty;
                $price = Input::get('unitPrice');
                $amount = $qtylast * $price;
                $tmpedit->qty = $qtylast;
                $tmpedit->amount = $amount;
            }else{
                $tmpedit->qty = Input::get('qty');
                $tmpedit->amount = Input::get('amount');
            }
            $tmpedit->purchaseorder_id = Input::get('poid');
            $tmpedit->product_id = Input::get('product_id');
            $tmpedit->unitPrice = Input::get('unitPrice');
            $tmpedit->recordStatus = 'a';
            $tmpedit->user_id = Auth::user()->id;
            $tmpedit->save();
            $pos=Purchaseordersd::findOrFail($poid);
            $brandid = Auth::user()->brand->id;
            $products = Brand::findOrFail($brandid)->products()->get();
            $potmps = Tmpeditpurchaseordercussd::where('purchaseorder_id','=',$poid)->where('recordStatus','!=','r')->get();
         return view('admin.saleSD.edit',compact('pos','potmps','products'));
        }
        if(Input::get('btn_back')){
            $tmps = Tmpeditpurchaseordercussd::where('purchaseorder_id','=',$id)->get();
            foreach ($tmps as $tmp) {
                $tmp->delete();
            }
            return redirect()->route('saleSD.index');
        }
        if(Input::get('btn_done')){
            $poid = Input::get('poid');
            $tmp = Tmpeditpurchaseordercussd::all();
            $totalAmount = $tmp->where('purchaseorder_id','=',$poid)->where('recordStatus','!=','r')->sum('amount');
            $discount = Purchaseordersd::where('id','=',$poid)->value('discount');
            $cod = Purchaseordersd::where('id','=',$poid)->value('cod');
            $Vtotal = $totalAmount  - $totalAmount * $discount /100;
            $grandTotal = $Vtotal - $Vtotal * $cod /100;
            $po = Purchaseordersd::findOrFail($poid);
            $po->totalAmount = $totalAmount;
            $po->grandTotal = $grandTotal;
            $po->save();
            DB::table('purchaseordersd_product')->where('purchaseordersd_id','=',$poid)->delete();
            $tmps = Tmpeditpurchaseordercussd::where('purchaseorder_id','=',$poid)->where('recordStatus','!=','r')->get();
                    foreach ($tmps as $tmp) {
                     DB::statement("INSERT INTO purchaseordersd_product (purchaseordersd_id, product_id, unitPrice, qty, amount, user_id) 
                        VALUES({$tmp->purchaseorder_id},{$tmp->product_id},{$tmp->unitPrice},{$tmp->qty},{$tmp->amount},{$tmp->user_id})");
                    }
            DB::table('tmpeditpurchaseordercussd')->where('purchaseorder_id','=',$poid)->delete();
           return redirect()->route('saleSD.index');
        }   
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
     public function getPopupCusSD()
    {
        $channels = Channel::pluck('name','id')->all();
        return view('include.popupCusSD',compact('channels'));
    }
    public function addOrderSDSale($proid, $qty, $price, $amount){
        $oldQty = Tmppurchaseordercussd::where('product_id','=',$proid)->where('user_id','=',Auth::user()->id)->value('qty');
        $user_id =Auth::user()->id;
        if($oldQty!=null){
            DB::statement("DELETE FROM tmppurchaseordercussd WHERE product_id={$proid} AND user_id={$user_id}");
                $newQty = (int)$qty;
                $qtylast = $oldQty + $newQty;
                $amount = $qtylast * $price;
            Tmppurchaseordercussd::create(['product_id'=>$proid,'qty'=>$qtylast,'unitPrice'=>$price,'amount'=>$amount,'user_id'=>Auth::user()->id]);
        }else{
            Tmppurchaseordercussd::create(['product_id'=>$proid,'qty'=>$qty,'unitPrice'=>$price,'amount'=>$amount,'user_id'=>Auth::user()->id]);
        }
        $tmpPurchaseOrders = Tmppurchaseordercussd::where('user_id','=',Auth::user()->id)->get();
        return response()->json($tmpPurchaseOrders);
    }
    public function showProductCussd(){
        $tmpdata = Tmppurchaseordercussd::where('user_id','=',Auth::user()->id)->get();
        return view('admin.saleSD.showProduct',compact('tmpdata'));
    }
    public function showEditcussd($poid)
    {
        $pos=Purchaseordersd::findOrFail($poid);
         $brandid = Auth::user()->brand->id;
        $products = Brand::findOrFail($brandid)->products()->get();
        $potmps = Tmpeditpurchaseordercussd::where('purchaseorder_id','=',$poid)->where('recordStatus','!=','r')->get();
         return view('admin.saleSD.edit',compact('pos','potmps','products'));
    }
    public function deleteProcussd(Request $request)
    {
        $poid = Input::get('poid');
        $proid = Input::get('proid');
        DB::statement("UPDATE tmpeditpurchaseordercussd SET recordStatus='r' WHERE purchaseorder_id={$poid} AND product_id={$proid}");
        return redirect(url('admin/showEditcussd',$poid));
    }
    public function getPopupEditProductEditCussd($poid,$proid)
    {
        $qty = Tmpeditpurchaseordercussd::where('purchaseorder_id','=',$poid)
                                      ->where('product_id','=',$proid)
                                      ->where('recordStatus','!=','r')
                                      ->value('qty');
        $price = Tmpeditpurchaseordercussd::where('purchaseorder_id','=',$poid)
                                      ->where('product_id','=',$proid)
                                      ->where('recordStatus','!=','r')
                                      ->value('unitPrice');
        return view('admin.saleSD.editPopup',compact('qty','proid','poid','price'));
    }
    public function updateProCussd(Request $request)
    {
        $poid = Input::get('poid');
        $proid = Input::get('proid');
        $qty = Input::get('qty');
        $unitPrice = Input::get('unitPrice');
        $amount = $qty * $unitPrice;
        DB::statement("UPDATE tmpeditpurchaseordercussd SET qty={$qty}, unitPrice={$unitPrice}, amount={$amount}, recordStatus='e' WHERE purchaseorder_id={$poid} AND product_id={$proid} AND recordStatus!='r'");
        return redirect(url('admin/showEditcussd',$poid));
    }
}
