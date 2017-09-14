<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Import;
use App\Product;
use App\Purchaseorder;
use App\Returnpro;
use App\Stockout;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductReturn extends Controller
{

    public function index()
    {
        $returnpro = Returnpro::all();
        return view('admin.returnProduct.index',compact('returnpro'));
    }
    public function viewProductReturn($returnProId,$status,$stockoutId){

        if(strtolower($status)=="s"){
            $returnpro = DB::table('product_returnpro')->where('returnpro_id',$returnProId)->selectRaw('product_id, sum(qtyreturn) as QR, sum(qtyorder) as QO, sum(qtyreturn)+sum(qtyorder) as TQ')->groupBy('product_id')->get();
            return view('admin.returnProduct.viewProductReturn',compact('returnpro'));
        }elseif(strtolower($status)=="a"){

            $id = Stockout::findOrFail($stockoutId)->value('purchaseorder_id');
            $purchaseorder =Purchaseorder::findOrFail($id);
            $purcheseData = $purchaseorder->products;
            return view('admin.returnProduct.viewProductReturnAll',compact('purcheseData'));

        }

    }

    public function create()
    {
        $stockout = Stockout::all();
        $user = User::all();
       return view('admin.returnProduct.create',compact('stockout','user'));
    }

    public function store(Request $request)
    {
        //
    }


    public function show($id)//show invoice detail onchange invoice and return by
    {
        $user_name="";
        $phone="";
        $location ="";
        if($id!=0){
            $stockoutID = Stockout::where('purchaseorder_id','=',$id)->value('id');
            $stockoutFilter = Stockout::findOrFail($stockoutID);
            $data = $stockoutFilter->imports;
            $Purchase = Purchaseorder::findOrFail($id);
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
        return view('admin.returnProduct.viewsInvoice',compact('data','stockoutID','user_name','phone','location','c'));
    }

    public  function SaveReturnAll($id,$userId){//save data that they are return all product
        $stockoutId = Returnpro::where('stockout_id','=',$id)->value('stockout_id');
        if($stockoutId){
            return "<h5 style='color: red;'>You cannot return all products on this invoice cuz you had returned some products on this invoice</h5>";
        }else{
            $result= DB::table('import_stockout')->where([['stockout_id','=',$id],['status','=',null],])->get();
            if($result->count()>0){
                foreach ($result as $re){
                    $Import_qty=0;
                    $UpQty=0;
                    $product=Product::findOrFail($re->product_id);
                    $product_qty = $product->qty;
                    $import_product = DB::table('import_product')->where([['importId','=',$re->import_id],['productId','=',$re->product_id],['expd','=',$re->expd]])->select('qty')->get();
                    foreach ($import_product as $q){
                        $Import_qty=$q->qty;
                        $UpQty =$Import_qty+$re->qty;
                    }
                    DB::table('import_product')->where([['importId','=',$re->import_id],['productId','=',$re->product_id],['expd','=',$re->expd],])->update(['qty'=>$UpQty]);
                    $product->qty = $product_qty+$re->qty;
                    $product->save();
                }
                DB::table('import_stockout')->where('stockout_id',$id)->update(['status'=>1]);
                $return = new Returnpro();
                $return->stockout_id=$id;
                $return->purchaseorder_id=0;
                $return->returnBy=$userId;
                $return->status="a";
                $return->save();
                return "<h5 style='color: darkblue;'>Returned successfully...</h5>";
            }
        }
    }

    public function save($stId, $Qty, $qty,$proId,$impId,$returnBy,$Inv){//save return one by one ( return product )

          $stockoutId = Returnpro::where('stockout_id','=',$stId)->value('stockout_id');
          $qtyorder = $Qty-$qty;
        if(!$stockoutId){

            $returnpro = new Returnpro();
            $returnpro->stockout_id = $stId;
            $returnpro->purchaseorder_id = 0;
            $returnpro->returnBy=$returnBy;
            $returnpro->status="s";
            $returnpro->save();
            $returnpro->products()->attach($proId,['qtyreturn'=>$qty,'qtyorder'=>$qtyorder]);

        }else{

            $Id = Returnpro::where('stockout_id','=',$stId)->value('id');
            $saveData = Returnpro::findOrFail($Id);
            $saveData->products()->attach($proId,['qtyreturn'=>$qty,'qtyorder'=>$qtyorder]);
        }

        $Import_Bqty =0;
        $product_Qty =Product::findOrFail($proId)->value('qty');
        $Import_qty=DB::table('import_product')->where([['importId','=',$impId],['productId','=',$proId],])->select('qty')->get();
        foreach ($Import_qty as $q){
            $Import_Bqty=$q->qty;
        }
        $Qty_update_import = $Import_Bqty + $qty;
        $Qty_update_product= $product_Qty + $qty;

        DB::table('import_product')->where([['importId','=',$impId],['productId','=',$proId],])->update(['qty'=>$Qty_update_import]);//Update QTY in table import_product
        DB::table('products')->where('id',$proId)->update(['qty'=>$Qty_update_product]);//Update QTY in table products
        DB::table('import_stockout')->where([['stockout_id','=',$stId],['import_id','=',$impId],['product_id','=',$proId],])->update(['status'=>1]);
        return  $this->show($Inv);

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
}
