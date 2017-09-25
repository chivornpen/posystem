<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Stockoutsd;
use App\Purchaseordersd;
use App\Customer;
use Auth;
use App\Exchangesd;
use App\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class ExchangesdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exchange = Exchangesd::all();
        return view('admin.ProductExchangesd.index',compact('exchange'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brandId = Auth::user()->brand->id;
        $stockoutsd = Stockoutsd::select('purchaseordersd_id')->where('brand_id','=',$brandId)->get();
        return view('admin.ProductExchangesd.create',compact('stockoutsd'));
    }
    public function createPoExchange()
    {
        $brandId = Auth::user()->brand->id;
        $exchange = Exchangesd::where('brand_id','=',$brandId)->get();
        return view('admin.ProductExchangesd.exchangeInvoice',compact('exchange'));
    }
    public function listInvoiceExchange($id)
    {
        if($id){
            $ex = Exchangesd::findOrFail($id);
            $view = $ex->products;
        }else{
            $view=false;
        }
        return view('admin.ProductExchangesd.viewExchangeInvoice',compact('view','id'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function showProductExchangeSd($id){

        $user_name="";
        $phone="";
        $location ="";

        if($id!=0){
            $stockoutID = Stockoutsd::where('purchaseordersd_id','=',$id)->value('id');
            $stockoutFilter = Stockoutsd::findOrFail($stockoutID);
            $data = $stockoutFilter->subimports;
            $Purchase = Purchaseordersd::findOrFail($id);
            $customer_id = $Purchase->customer_id;
            if($customer_id){
                $user_name =$Purchase->customer->name;
                $phone=$Purchase->customer->contactNo;
                $location=$Purchase->customer->location;
            }else{
                $phone = $Purchase->user->contactNum;
                $user_name= Customer::where('contactNo','=',$phone)->value('name');
                $location=Customer::where('contactNo','=',$phone)->value('location');
            }
        }else{
            $data=false;
        }

        return view('admin.ProductExchangesd.viewInvoice',compact('data','stockoutID','user_name','phone','location'));
    }
    public function saveExchangesd($importId, $productId,$qty, $expd, $stockoutId){
        $check= Exchangesd::where('stockoutsd_id','=',$stockoutId)->value('stockoutsd_id');
        $brandId = Auth::user()->brand->id;
        $user_name="";
        $phone="";
        $location ="";
        if(!$check){
            $insert = new Exchangesd();
            $insert->stockoutsd_id=$stockoutId;
            $insert->brand_id = $brandId;
            $insert->save();
            $insert->products()->attach($productId,['qty'=>$qty,'expd'=>$expd]);
        }else{
           $id= Exchangesd::where('stockoutsd_id','=',$stockoutId)->value('id');
           $insert = Exchangesd::findOrFail($id);
           $insert->products()->attach($productId,['qty'=>$qty,'expd'=>$expd]);
        }
        $Import_Bqty =0;
        $product_Qty =Product::findOrFail($productId)->value('qty');
        $Import_qty=DB::select("select qty from subimport_product WHERE subimport_id={$importId} AND product_id={$productId}");
        foreach ($Import_qty as $q){
            $Import_Bqty=$q->qty;
        }
        $Qty_update_import = $Import_Bqty + $qty;
        $Qty_update_product= $product_Qty + $qty;

        DB::table('subimport_product')->where([['subimport_id','=',$importId],['product_id','=',$productId],])->update(['qty'=>$Qty_update_import]);//Update QTY in table import_product
        DB::table('products')->where('id',$productId)->update(['qty'=>$Qty_update_product]);//Update QTY in table products
        DB::table('import_stockoutsd')->where([['stockoutsd_id','=',$stockoutId],['subimport_id','=',$importId],['product_id','=',$productId],])->update(['status'=>1]);

        $stockoutFilter = Stockoutsd::findOrFail($stockoutId);
        $data = $stockoutFilter->subimports;
        //dd($data);
        $purchaseorderId = $stockoutFilter->purchaseordersd_id;
        $stockoutID = $stockoutId;
        $Purchase = Purchaseordersd::findOrFail($purchaseorderId);
        $customer_id = $Purchase->customer_id;
        if($customer_id){
            $user_name =$Purchase->customer->name;
            $phone=$Purchase->customer->contactNo;
            $location=$Purchase->customer->location;
        }else{
            $phone = $Purchase->user->contactNum;
            $user_name= Customer::where('contactNo','=',$phone)->value('name');
            $location=Customer::where('contactNo','=',$phone)->value('location');
        }


        return view('admin.ProductExchangesd.viewInvoice',compact('data','stockoutID','user_name','phone','location'));
    }
    public function viewDetailExchangesd($id){
        $exchange = Exchangesd::findOrFail($id);
        $data = $exchange->products;
        $stockoutId = $exchange->stockoutsd_id;
        $stockout = Stockoutsd::findOrFail($stockoutId);
        $purchaseoderId= $stockout->purchaseordersd_id;

        $user_name="";
        $Purchase = Purchaseordersd::findOrFail($purchaseoderId);
        $customer_id = $Purchase->customer_id;
        if($customer_id){
            $user_name =$Purchase->customer->name;
        }else{
            $contact = $Purchase->user->contactNum;
            $user_name= Customer::where('contactNo','=',$contact)->value('name');
        }

        return view('admin.ProductExchangesd.detail',compact('data','user_name'));
    }
    public function createNewInvoice($id){

           if($id){
               $user_id =0;
               $customer_id=0;
               $now = Carbon::now()->toDateString();
               $result = DB::table('exchangesd_product')->selectRaw('product_id, sum(qty) as total')->where('exchangesd_id','=',$id)->groupBy('product_id')->get();
               $exchange = Exchangesd::findOrFail($id);
               $stockoutID=$exchange->stockoutsd->id;
               $stockout = Stockoutsd::findOrFail($stockoutID);
               $purchaseorderID=$stockout->purchaseordersd_id;
               $purchaseordersd = Purchaseordersd::findOrFail($purchaseorderID);
               $user_id= $purchaseordersd->user_id;
               $customer_id= $purchaseordersd->customer_id;
               $purchaseordersd = new Purchaseordersd();
               $purchaseordersd->poDate= $now;
               $purchaseordersd->dueDate= $now;
               $purchaseordersd->totalAmount= 0;
               $purchaseordersd->discount= 0;
               $purchaseordersd->user_id= $user_id;
               $purchaseordersd->customer_id= $customer_id;
               $purchaseordersd->cod= 0;
               $purchaseordersd->isGenerate= 0;
               $purchaseordersd->grandTotal= 0;
               $purchaseordersd->isDelivery= 0;
               $purchaseordersd->save();
               $purchaseorderId = $purchaseordersd->id;
                       foreach ($result as $re){
                           $purchaseordersd->products()->attach($re->product_id,['qty'=>$re->total,'unitPrice'=>0,'amount'=>0,'user_id'=>$user_id]);
                       }

               $exchange->purchaseordersd_id = $purchaseorderId;
               $exchange->save();
               return "<div style='color: #0d6aad; margin-left: 10px;'>created successfully...</div>";
           }
    }

}
