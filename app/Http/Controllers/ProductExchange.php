<?php

namespace App\Http\Controllers;

use App\Exchange;
use App\Import;
use App\Product;
use App\Stockout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductExchange extends Controller
{

    public function index()
    {
       return view('admin.ProductExchange.index');
    }


    public function create()
    {
        $stockout = Stockout::select('purchaseorder_id')->get();
//        dd($stockout);
        return view('admin.ProductExchange.create',compact('stockout'));
    }


    public function showRecord($id){
        if($id!=0){
            $stockoutID = Stockout::where('purchaseorder_id','=',$id)->value('id');
            $stockoutFilter = Stockout::findOrFail($stockoutID);
            $data = $stockoutFilter->imports;
        }else{
            $data=false;
        }

        return view('admin.ProductExchange.viewInvoice',compact('data','stockoutID'));
    }
    public function saveRecord($importId, $productId,$qty, $expd, $stockoutId){
        $check= Exchange::where('stockout_id','=',$stockoutId)->value('stockout_id');
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
        $stockoutID = $stockoutId;
        return view('admin.ProductExchange.viewInvoice',compact('data','stockoutID'));
    }


}
