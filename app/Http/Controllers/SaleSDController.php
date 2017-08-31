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


class SaleSDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sale = Purchaseordersd::where('customer_id','!=',null)->get();
        return view('admin.saleSD.index',compact('sale'));
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
        //dd($request->all());
         if(Input::get('btn_save')){
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
            $tmps = TpmPurchaseOrder::where('user_id','=',Auth::user()->id)->get();
                foreach ($tmps as $tmp) {
                $po->products()->attach($tmp->product_id,
                    ['unitPrice'=>$tmp->unitPrice,
                    'qty'=>$tmp->qty,
                    'amount'=>$tmp->amount,
                    'user_id'=>$tmp->user_id]);
                }
            $tmps = TpmPurchaseOrder::where('user_id','=',Auth::user()->id)->get();
            foreach ($tmps as $tmp) {
                $tmp->delete();
            }
            $pocuss = Purchaseordersd::where('customer_id','!=',null)->get();
            return view('admin.saleSD.index',compact('pocuss'));
        }
        //------------------btn_back---------------
        if(Input::get('btn_back')){
            $tmps = TpmPurchaseOrder::where('user_id','=',Auth::user()->id)->get();
            foreach ($tmps as $tmp) {
                $tmp->delete();
            }
            return redirect()->back();
        }
        //------------------btn_cancel--------------------------
        if(Input::get('btn_cancel')){
            $tmps = TpmPurchaseOrder::where('user_id','=',Auth::user()->id)->get();
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
     public function getPopupCusSD()
    {
        $channels = Channel::pluck('name','id')->all();
        return view('include.popupCusSD',compact('channels'));
    }
    public function addOrderSDSale($proid, $qty, $price, $amount){
        $oldQty = TpmPurchaseOrder::where('product_id','=',$proid)->where('user_id','=',Auth::user()->id)->value('qty');
        $user_id =Auth::user()->id;
        if($oldQty!=null){
            DB::statement("DELETE FROM tmppurchaseoders WHERE product_id={$proid} AND user_id={$user_id}");
                $newQty = (int)$qty;
                $qtylast = $oldQty + $newQty;
                $amount = $qtylast * $price;
            TpmPurchaseOrder::create(['product_id'=>$proid,'qty'=>$qtylast,'unitPrice'=>$price,'amount'=>$amount,'user_id'=>Auth::user()->id]);
        }else{
            TpmPurchaseOrder::create(['product_id'=>$proid,'qty'=>$qty,'unitPrice'=>$price,'amount'=>$amount,'user_id'=>Auth::user()->id]);
        }
        $tmpPurchaseOrders = TpmPurchaseOrder::where('user_id','=',Auth::user()->id)->get();
        return response()->json($tmpPurchaseOrders);
    }

}
