<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subimport;
use App\Product;
use Auth;
use App\Stockout;
use App\Stockoutsd;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Brand;
use App\User;
use App\Purchaseorder;

class SDStockReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->position->name == 'SD'){
            $brands = Brand::all();
            $brandId = Auth::user()->brand_id;
            $user_id=array();
            $users = User::where('brand_id','=',$brandId)->get();
            foreach ($users as $user) {
                $user_id[]=$user->id;
            }
            $purchaseorders = Purchaseorder::whereIn('user_id',$user_id)->get();
            $brandProducts = DB::table('brand_product')->where('brand_id','=',$brandId)->get();
        }else{
            $brandId = 0;
            $brands = Brand::all();
            $brandProducts = DB::table('brand_product')->select('product_id')->distinct()->get();
        }
        return view('admin.SdStockReport.reportstockbalance',compact('brandProducts','brands','brandId','purchaseorders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->position->name == 'SD'){
            $brands = Brand::all();
            $brandId = Auth::user()->brand->id;
            $brandProducts = DB::table('brand_product')->where('brand_id','=',$brandId)->get();
            $subimport = Subimport::where('brand_id','=',$brandId)->get();
        }else{
            $brandId = 0;
            $brands = Brand::all();
            $brandProducts = DB::table('brand_product')->select('product_id')->distinct()->get();
            $subimport = Subimport::all();
        }
        return view('admin.SdStockReport.reportstockin',compact('brandProducts','subimport','brands','brandId'));
    }
    public function sdreportstockout()
    {
        if(Auth::user()->position->name == 'SD'){
            $brands = Brand::all();
            $brandId = Auth::user()->brand->id;
            $brandProducts = DB::table('brand_product')->where('brand_id','=',$brandId)->get();
            $stockoutsds = Stockoutsd::where('brand_id','=',$brandId)->get();
        }else{
            $brandId = 0;
            $brands = Brand::all();
            $brandProducts = DB::table('brand_product')->select('product_id')->distinct()->get();
            $stockoutsds = Stockoutsd::all();
        }
        return view('admin.SdStockReport.reportstockout',compact('brandProducts','stockoutsds','brands','brandId'));
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
    public function searchIn($brand_id,$startDate, $endDate)
    {
        if($brand_id>0 && $startDate==0 && $endDate==0 ){
            $begin = null;
            $end = null;
            $brandProducts = DB::table('brand_product')->where('brand_id','=',$brand_id)->get();
            $subimport = Subimport::where('brand_id','=',$brand_id)->get();
        }elseif($brand_id==0 && $startDate>0 && $endDate>0){
            $startDate = strtotime( "$startDate" );  
            $endDate = strtotime( "$endDate" ); 
            $begin = date('Y-m-d', $startDate );
            $end = date('Y-m-d', $endDate );
            $brandProducts = DB::table('brand_product')->select('product_id')->distinct()->get();
            $subimport = Subimport::whereBetween('subimportDate', [$begin, $end])->get();
        }elseif($brand_id>0 && $startDate>0 && $endDate>0){
            $startDate = strtotime( "$startDate" );  
            $endDate = strtotime( "$endDate" ); 
            $begin = date('Y-m-d', $startDate );
            $end = date('Y-m-d', $endDate );
            $brandProducts = DB::table('brand_product')->where('brand_id','=',$brand_id)->get();
            $subimport = Subimport::whereBetween('subimportDate', [$begin, $end])->where('brand_id','=',$brand_id)->get();
        }elseif($brand_id==0 && $startDate==0 && $endDate==0 ){
            $begin = null;
            $end = null;
            $brands = Brand::all();
            $brandProducts = DB::table('brand_product')->select('product_id')->distinct()->get();
            $subimport = Subimport::all();
        }
        return view('admin.SdStockReport.searchreportstockin',compact('brandProducts','subimport','begin','end'));
    }
    public function searchOut($brand_id,$startDate, $endDate)
    {
        if($brand_id>0 && $startDate==0 && $endDate==0 ){
            $begin = null;
            $end = null;
            $brandProducts = DB::table('brand_product')->where('brand_id','=',$brand_id)->get();
            $stockoutsds = Stockoutsd::where('brand_id','=',$brand_id)->get();
        }elseif($brand_id==0 && $startDate>0 && $endDate>0){
            $startDate = strtotime( "$startDate" );  
            $endDate = strtotime( "$endDate" ); 
            $begin = date('Y-m-d', $startDate );
            $end = date('Y-m-d', $endDate );
            $brandProducts = DB::table('brand_product')->select('product_id')->distinct()->get();
            $stockoutsds = Stockoutsd::whereBetween('stockoutDate', [$begin, $end])->get();
        }elseif($brand_id>0 && $startDate>0 && $endDate>0){
            $startDate = strtotime( "$startDate" );  
            $endDate = strtotime( "$endDate" ); 
            $begin = date('Y-m-d', $startDate );
            $end = date('Y-m-d', $endDate );
            $brandProducts = DB::table('brand_product')->where('brand_id','=',$brand_id)->get();
            $stockoutsds = Stockoutsd::whereBetween('stockoutDate', [$begin, $end])->where('brand_id','=',$brand_id)->get();
        }elseif($brand_id==0 && $startDate==0 && $endDate==0 ){
            $begin = null;
            $end = null;
            $brands = Brand::all();
            $brandProducts = DB::table('brand_product')->select('product_id')->distinct()->get();
            $stockoutsds = Stockoutsd::all();
        }
        return view('admin.SdStockReport.searchreportstockout',compact('brandProducts','stockoutsds','begin','end'));
    }
    public function searchBalance($brand_id)
    {
            $brands = Brand::all();
            $brandId = $brand_id;
            $user_id=array();
            $users = User::where('brand_id','=',$brandId)->get();
            foreach ($users as $user) {
                $user_id[]=$user->id;
            }
            $purchaseorders = Purchaseorder::whereIn('user_id',$user_id)->get();
            $brandProducts = DB::table('brand_product')->where('brand_id','=',$brandId)->get();
        return view('admin.SdStockReport.searchreportstockbalance',compact('brandProducts','brands','purchaseorders','brandId'));
    }
}
