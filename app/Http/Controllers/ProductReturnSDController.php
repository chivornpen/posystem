<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Purchaseordersd;
use App\Customer;
use Auth;
use App\Exchangesd;
use App\Stockoutsd;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\User;
use App\Returnprosd;
class ProductReturnSDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brandId= Auth::user()->brand->id;
        $returnpro = Returnprosd::where('brand_id','=',$brandId)->get();
        // dd($returnpro);
        return view('admin.returnProductSd.index',compact('returnpro'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brandId = Auth::user()->brand->id;
        $stockout = Stockoutsd::where('brand_id','=',$brandId)->where('status','=',null)->get();
        $user = User::where('brand_id','=',$brandId)->get();
       return view('admin.returnProductSd.create',compact('stockout','user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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
    public function showInvoiceReturn($id)
    {
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
        return view('admin.returnProductSd.viewsInvoice',compact('data','stockoutID','user_name','phone','location','c'));
    }
    public function returnProductOneByOne($stId, $Qty, $qty,$proId,$impId,$returnBy,$Inv)
    {
        $brandId=Auth::user()->brand->id;
        $stockoutId = Returnprosd::where('stockoutsd_id','=',$stId)->value('stockoutsd_id');
          $qtyorder = $Qty-$qty;
        if(!$stockoutId){
            $returnpro = new Returnprosd();
            $returnpro->stockoutsd_id = $stId;
            $returnpro->brand_id = $brandId;
            $returnpro->purchaseordersd_id = 0;
            $returnpro->returnBy=$returnBy;
            $returnpro->status="s";
            $returnpro->save();
            $returnpro->products()->attach($proId,['qtyreturn'=>$qty,'qtyorder'=>$qtyorder]);

        }else{

            $Id = Returnprosd::where('stockoutsd_id','=',$stId)->value('id');
            $saveData = Returnprosd::findOrFail($Id);
            $saveData->products()->attach($proId,['qtyreturn'=>$qty,'qtyorder'=>$qtyorder]);
        }
        $Import_Bqty =0;
        //$product_Qty =Product::findOrFail($proId)->value('qty');
        $bqtys = DB::table('brand_product')->where([['product_id','=',$proId],['brand_id','=',$brandId],])->select('qty')->get();
        foreach ($bqtys as $bqty) {
            $product_Qty=$bqty->qty;
        }
        $Import_qty=DB::table('subimport_product')->where([['subimport_id','=',$impId],['product_id','=',$proId],])->select('qty')->get();
        foreach ($Import_qty as $q){
            $Import_Bqty=$q->qty;
        }
        $Qty_update_import = $Import_Bqty + $qty;
        $Qty_update_product= $product_Qty + $qty;

        DB::table('subimport_product')->where([['subimport_id','=',$impId],['product_id','=',$proId],])->update(['qty'=>$Qty_update_import]);//Update QTY in table import_product
        DB::table('brand_product')->where([['product_id','=',$proId],['brand_id','=',$brandId],])->update(['qty'=>$Qty_update_product]);//Update QTY in table products
        DB::table('import_stockoutsd')->where([['stockoutsd_id','=',$stId],['subimport_id','=',$impId],['product_id','=',$proId],])->update(['status'=>1]);
        return  $this->showInvoiceReturn($Inv);
    }
    public function ReturnAllProduct($id,$userId)
    {
        $brandId=Auth::user()->brand->id;
        $stockoutId = Returnprosd::where('stockoutsd_id','=',$id)->value('stockoutsd_id');
        if($stockoutId){
            return "<h5 style='color: red;'>You cannot return all products on this invoice cuz you had returned some products on this invoice</h5>";
        }else{
            $result= DB::table('import_stockoutsd')->where([['stockoutsd_id','=',$id],['status','=',null],])->get();
            if($result->count()>0){
                foreach ($result as $re){
                    $productId = $re->product_id;
                    $Import_qty=0;
                    $UpQty=0;
                    $bqtys = DB::table('brand_product')->where([['product_id','=',$productId],['brand_id','=',$brandId],])->select('qty')->get();
                    foreach ($bqtys as $bqty) {
                        $product_qty=$bqty->qty;
                    }
                    $import_product = DB::table('subimport_product')->where([['subimport_id','=',$re->subimport_id],['product_id','=',$productId],['brand_id','=',$brandId],['expd','=',$re->expd]])->select('qty')->get();
                    foreach ($import_product as $q){
                        $Import_qty=$q->qty;
                        $UpQty =$Import_qty+$re->qty;
                    }
                    DB::table('subimport_product')->where([['subimport_id','=',$re->subimport_id],['product_id','=',$productId],['brand_id','=',$brandId],['expd','=',$re->expd],])->update(['qty'=>$UpQty]);
                    $product_Q = $product_qty+$re->qty;
                    DB::table('brand_product')->where([['product_id','=',$productId],['brand_id','=',$brandId],])->update(['qty'=>$product_Q]);//Update QTY in table products
                }
                DB::table('import_stockoutsd')->where('stockoutsd_id',$id)->update(['status'=>1]);
                $return = new Returnprosd();
                $return->stockoutsd_id=$id;
                $return->brand_id = $brandId;
                $return->purchaseordersd_id=0;
                $return->returnBy=$userId;
                $return->status="a";
                $return->save();
                return "<h5 style='color: darkblue;'>Returned successfully...</h5>";
            }
        }
    }
    public function viewProductReturn($returnProId,$status,$stockoutId)
    {

        if(strtolower($status)=="s"){
            $returnpro = DB::table('product_returnprosd')->where('returnprosd_id',$returnProId)->selectRaw('product_id, sum(qtyreturn) as QR, sum(qtyorder) as QO, sum(qtyreturn)+sum(qtyorder) as TQ')->groupBy('product_id')->get();
            return view('admin.returnProductSd.viewProductReturn',compact('returnpro'));
        }elseif(strtolower($status)=="a"){

            $id = Stockoutsd::findOrFail($stockoutId)->value('purchaseordersd_id');
            $purchaseorder =Purchaseordersd::findOrFail($id);
            $purcheseData = $purchaseorder->products;
            return view('admin.returnProductSd.viewProductReturnAll',compact('purcheseData'));

        }

    }///--------------------
    public function createInvoiceReturnzSd()
    {
        $brandId=Auth::user()->brand->id;
        $productReturn = Returnprosd::where([['isGenerate','=',0],['status','=','s'],['brand_id','=',$brandId],])->get();
        return view('admin.returnProductSd.invoiceReturnProductCreate',compact('productReturn'));
    }
    //show invoice have to return to customer
    //show conten invoice return when chose in drop down
    public function showContentInvReturn($returnId,$status){

        if($returnId!=0){
            $returnpro = DB::table('product_returnprosd')->where('returnprosd_id',$returnId)->selectRaw('product_id, sum(qtyreturn) as QR, sum(qtyorder) as QO, sum(qtyreturn)+sum(qtyorder) as TQ')->groupBy('product_id')->get();
        }else{
            $returnpro=false;
        }
        return view('admin.returnProductSd.viewProReturnInvoice',compact('returnpro','status','returnId'));
    }
    //create Invoice Product Return
    public function productReturnCreateInv($returnId,$status){
        $productId = 0;
        $quantities = 0;
        $unitprice = 0;
        $amount = 0;
        $discount = 0;
        $cod = 0;
        $now = Carbon::now()->toDateString();
        $returnPro = Returnprosd::findOrFail($returnId)->value('stockoutsd_id');
        $purchaseOrderId = Stockoutsd::findOrFail($returnPro)->value('purchaseordersd_id');


//Have problem here have to solve it as soon as............
        
        $purchaseorder = Purchaseordersd::findOrFail($purchaseOrderId);
        $user_id= $purchaseorder->user_id;
        $customer_id= $purchaseorder->customer_id;
        $discount= $purchaseorder->discount;
        $cod = $purchaseorder->cod;

        $purchaseorder = new Purchaseordersd();
        $purchaseorder->poDate= $now;
        $purchaseorder->dueDate= $now;
        $purchaseorder->totalAmount= 0;
        $purchaseorder->discount= $discount;
        $purchaseorder->user_id= $user_id;
        $purchaseorder->customer_id= $customer_id;
        $purchaseorder->cod= $cod;
        $purchaseorder->isGenerate= 0;
        $purchaseorder->grandTotal= 0;
        $purchaseorder->isDelivery= 1;
        $purchaseorder->save();
        $purchaseorderId = $purchaseorder->id;

        $returnpro = DB::table('product_returnprosd')->where('returnprosd_id',$returnId)->selectRaw('product_id, sum(qtyreturn) as QR, sum(qtyorder) as QO, sum(qtyreturn)+sum(qtyorder) as TQ')->groupBy('product_id')->get();
        foreach ($returnpro as $p){
            $unitprice= DB::table('purchaseordersd_product')->where([['purchaseordersd_id','=',$purchaseOrderId],['product_id','=',$p->product_id],])->value('unitPrice');
               $product_id = $p->product_id;
                if($status==1){//company paid
                    $quantities= $p->QR;
//                  echo "$amount =$amount+($unitprice*$quantities)"."<br>";
                    $pAmount=($unitprice*$quantities);
                    $amount =$amount+($unitprice*$quantities);
                    $purchaseorder->products()->attach($product_id,['qty'=>$quantities,'unitPrice'=>$unitprice,'amount'=>$pAmount,'user_id'=>$user_id]);
                }elseif($status==2){//customer paid
                    $quantities= $p->QO;
//                  echo "$amount =$amount+($unitprice*$quantities)"."<br>";
                    $pAmount=($unitprice*$quantities);
                    $amount =$amount+($unitprice*$quantities);
                    if($quantities==0){
                        $purchaseorder->products()->attach($product_id,['qty'=>$quantities,'unitPrice'=>$unitprice,'amount'=>$pAmount,'user_id'=>$user_id]);
                    }
                }
        }
        $grandTotal = $amount-($amount*$discount/100);
        $grandTotals = $grandTotal-($grandTotal*$cod/100);

        $purchaseorderUpdate = Purchaseordersd::findOrFail($purchaseorderId);
        $purchaseorderUpdate->totalAmount= $amount;
        $purchaseorderUpdate->grandTotal= round($grandTotals,2,PHP_ROUND_HALF_UP);
        $purchaseorderUpdate->save();

        $returnProduct = Returnprosd::findOrFail($returnId);
        $returnProduct->isGenerate=1;
        $returnProduct->purchaseordersd_id = $purchaseorderId;
        $returnProduct->save();

        $stockout = Stockoutsd::findOrFail($returnPro);
        $stockout->status=1;
        $stockout->save();

    }
}
