<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Exchange;
use App\Import;
use App\Product;
use App\Purchaseorder;
use App\Stockout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductExchange extends Controller
{

    public function index()
    {
        $exchange = Exchange::all();
       return view('admin.ProductExchange.index',compact('exchange'));
    }

    //View Detail of Exchange
    public function detail($id){
        $exchange = Exchange::findOrFail($id);
        $data = $exchange->products;

        $stockoutId = $exchange->stockout_id;
        $stockout = Stockout::findOrFail($stockoutId);
        $purchaseoderId= $stockout->purchaseorder_id;

        $user_name="";
        $Purchase = Purchaseorder::findOrFail($purchaseoderId);
        $customer_id = $Purchase->customer_id;
        if($customer_id){
            $user_name =$Purchase->customer->name;
        }else{
            $contact = $Purchase->user->contactNum;
            $user_name= Customer::where('contactNo','=',$contact)->value('name');
        }

        return view('admin.ProductExchange.detail',compact('data','user_name'));
    }


    public function create()//show Invoice number in comboBox
    {
        $stockout = Stockout::where('status','=',0)->select('purchaseorder_id')->get();
        return view('admin.ProductExchange.create',compact('stockout'));
    }


    public function showRecord($id){

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

        return view('admin.ProductExchange.viewInvoice',compact('data','stockoutID','user_name','phone','location'));
    }


    public function saveRecord($importId, $productId,$qty, $expd, $stockoutId){//Exchange product
        $check= Exchange::where('stockout_id','=',$stockoutId)->value('stockout_id');
        $user_name="";
        $phone="";
        $location ="";
        if(!$check){
            $insert = new Exchange();
            $insert->stockout_id=$stockoutId;
            $insert->save();
            $insert->products()->attach($productId,['qty'=>$qty,'expd'=>$expd]);
        }else{
           $id= Exchange::where('stockout_id','=',$stockoutId)->value('id');
           $insert = Exchange::findOrFail($id);
           $insert->products()->attach($productId,['qty'=>$qty,'expd'=>$expd]);
        }
        $Import_Bqty =0;
        $product_Qty =Product::findOrFail($productId)->value('qty');
        $Import_qty=DB::select("select qty from import_product WHERE importId={$importId} AND productId={$productId}");
        foreach ($Import_qty as $q){
            $Import_Bqty=$q->qty;
        }
        $Qty_update_import = $Import_Bqty + $qty;
        $Qty_update_product= $product_Qty + $qty;

        DB::table('import_product')->where([['importId','=',$importId],['productId','=',$productId],])->update(['qty'=>$Qty_update_import]);//Update QTY in table import_product
        DB::table('products')->where('id',$productId)->update(['qty'=>$Qty_update_product]);//Update QTY in table products
        DB::table('import_stockout')->where([['stockout_id','=',$stockoutId],['import_id','=',$importId],['product_id','=',$productId],])->update(['status'=>1]);

        $stockoutFilter = Stockout::findOrFail($stockoutId);
        $data = $stockoutFilter->imports;
        $purchaseorderId = $stockoutFilter->purchaseorder_id;
        $stockoutID = $stockoutId;

        $Purchase = Purchaseorder::findOrFail($purchaseorderId);
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


        return view('admin.ProductExchange.viewInvoice',compact('data','stockoutID','user_name','phone','location'));
    }


}
