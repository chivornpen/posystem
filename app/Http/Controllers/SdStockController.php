<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Purchaseorder;
use App\Subimport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SdStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brand = Brand::all();
        $user_brand = Auth::user()->brand_id;//
        if($user_brand!=0 && $user_brand!=null){
            $brand_id = Auth::user()->brand->id;
            $brand_product = Brand::findOrFail($brand_id)->products()->get();
            $subimport = Subimport::where('brand_id','=',$brand_id)->get();
        }else{
            $brand_product = Brand::where('user_id','=',0)->get();
            $subimport = Subimport::where('brand_id','=',0)->get();
        }
        return view('admin.SD_Stock_in.index',compact('brand_product','subimport', 'brand'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        if($id==0){
            $brand_product = Brand::where('user_id','=',0)->get();
            $subimport = Subimport::where('brand_id','=',0)->get();
        }else{
            $brand_product = Brand::findOrFail($id)->products()->get();
            $subimport = Subimport::where('brand_id','=',$id)->get();
        }
        return view('admin.SD_Stock_in.admin_view',compact('brand_product','subimport'));
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



    public function ShowCurrentRecordSdStock($id){

        $subimport = Subimport::findOrFail($id);
        return view('admin.SD_Stock_in.current',compact('subimport'));
    }
    public function ShowHistoryRecordSdStock($id){

        $hisStoriesimport = Purchaseorder::findOrFail($id);
        return view('admin.SD_Stock_in.histories',compact('hisStoriesimport'));
    }
}
