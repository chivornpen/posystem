<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\Import;
use App\Stockout;
use App\History;
use App\Exchange;
use App\Returnpro;
use Illuminate\Support\Facades\DB;

class StockReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productBalance = Product::all();
        return view('admin.stockReport.reportStockBalance',compact('productBalance'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();
        $import = Import::all();
        return view('admin.stockReport.reportStockIn',compact('products','import'));
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
    public function saerchDateStockIn($startDate, $endDate)
    {
        $startDate = strtotime( "$startDate" );  
        $endDate = strtotime( "$endDate" ); 
        $begin = date('Y-m-d', $startDate );
        $end = date('Y-m-d', $endDate );
        $products = Product::all();
        $import = Import::whereBetween('impDate', [$begin, $end])->get();
        return view('admin.stockReport.searchdatestockin',compact('products','import','begin','end'));
    }
    public function saerchDateStockOut($startDate, $endDate)
    {
        $startDate = strtotime( "$startDate" );  
        $endDate = strtotime( "$endDate" ); 
        $begin = date('Y-m-d', $startDate );
        $end = date('Y-m-d', $endDate );
        $products = Product::all();
        $stockOut = Stockout::whereBetween('stockoutDate', [$begin, $end])->get();
        return view('admin.stockReport.searchdatestockout',compact('products','stockOut','begin','end'));
    }
    public function reportStockOut(){
        $products = Product::all();
        $stockOut = Stockout::all();
        return view('admin.stockReport.reportStockOut',compact('products','stockOut'));
    }
    public function reportStockReturn()
    {
        $returnpros = Returnpro::where('purchaseorder_id','!=',0)->get();
        $products = Product::all();
       return view('admin.stockReport.reportstockreturnpro',compact('returnpros','products')); 
    }
    public function reportStockExchange()
    {
        $echanges = Exchange::all();
        $products = Product::all();
       return view('admin.stockReport.reportstockexchnge',compact('echanges','products')); 
    }
    public function saerchDateStockExchange($startDate, $endDate)
    {
        $startDate = strtotime( "$startDate" );  
        $endDate = strtotime( "$endDate" ); 
        $begin = date('Y-m-d', $startDate );
        $end = date('Y-m-d', $endDate );
        $products = Product::all();
        $echanges = Exchange::whereBetween('created_at', [$begin, $end])->get();
        return view('admin.stockReport.searchdatestockexchange',compact('products','echanges','begin','end'));
    }
    public function saerchDateStockReturnpro($startDate, $endDate)
    {
        $startDate = strtotime( "$startDate" );  
        $endDate = strtotime( "$endDate" ); 
        $begin = date('Y-m-d', $startDate );
        $end = date('Y-m-d', $endDate );
        $products = Product::all();
        $returnpros = Returnpro::whereBetween('created_at', [$begin, $end])->get();
        return view('admin.stockReport.searchdatestockreturnpro',compact('products','returnpros','begin','end'));
    }
}
