<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Product;
use App\Purchaseorder;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class reportController extends Controller
{
    public function index(){

        $customer = Customer::all();
        return view('admin.report.index',compact('customer'));
    }
    public function show($data){//Live search in customer list
        if($data!=0 || $data!=null){
            $customer =DB::select("SELECT * FROM customers WHERE name LIKE '%".$data."%'OR contactNo LIKE '%".$data."%' OR location LIKE'%".$data."%'");
            return view('admin.report.search',compact('customer'));
        }else{
            $customer = Customer::all();
            return view('admin.report.search',compact('customer'));
        }

    }

    public function SaleReport(){//sale report

        $users = DB::table('positions')->join('users','users.position_id','=','positions.id')->select('users.nameDisplay','users.id')->where('positions.name','sale')->orwhere('positions.name','sd')->get();
        $product = Product::all();
        $purchaseorder = Purchaseorder::where('isDelivery','=',1)->get();
        return view('admin.report.saleReport',compact('product','purchaseorder','users'));

    }

    public function SaleReportSearch($saleName, $startDate, $endDate){
        $start=$startDate;
        $end=$endDate;

        if($start==0 && $end==0 && $saleName==0){
            $product = Product::all();
            $purchaseorder = Purchaseorder::where('isDelivery','=',1)->get();
            return view('admin.report.SaleReportSearch',compact('product','purchaseorder'));
        }
        //dd($start.$end.$saleName);
        if($startDate && $endDate !=0){
            $start= Carbon::parse($startDate)->format('Y-m-d');
            $end= Carbon::parse($endDate)->format('Y-m-d');
        }

        if($saleName==0){//Search between
            $product = Product::all();
            $purchaseorder = Purchaseorder::whereBetween('poDate',[$start,$end])->get();
            return view('admin.report.SaleReportSearch',compact('product','purchaseorder'));
        }elseif($start==0 || $end==0){//search by user ID
            $product = Product::all();
            $purchaseorder = Purchaseorder::where('user_id',$saleName)->get();
            return view('admin.report.SaleReportSearch',compact('product','purchaseorder'));
        }elseif($start!=0 && $end!=0 && $saleName!=0){
            $product = Product::all();
            $purchaseorder = Purchaseorder::where('user_id',$saleName)->whereBetween('poDate',[$start,$end])->get();
            return view('admin.report.SaleReportSearch',compact('product','purchaseorder'));
        }


    }

    public function paymentReport(){
        $customer = Customer::all();
//            DB::table('positions')->join('users','users.position_id','=','positions.id')->select('users.nameDisplay','users.id')->where('positions.name','sale')->orwhere('positions.name','sd')->get();
        $product = Product::all();
        $purchaseorder = Purchaseorder::where([['isDelivery',1],['paid','!=',0],['cradit',0]])->get();
        return view('admin.report.paymentReport',compact('product','purchaseorder','customer'));
    }

    public function paymentReportSearch($custName, $startDate, $endDate){

        $start=$startDate;
        $end=$endDate;

        if($start==0 && $end==0 && $custName==0){
            $product = Product::all();
            $purchaseorder = Purchaseorder::where([['isDelivery',1],['paid','!=',0],['cradit',0]])->get();
            return view('admin.report.paymentSearch',compact('product','purchaseorder'));
        }
        //dd($start.$end.$custName);
        if($startDate && $endDate !=0){
            $start= Carbon::parse($startDate)->format('Y-m-d');
            $end= Carbon::parse($endDate)->format('Y-m-d');
        }

        if($custName==0){//Search between
            $product = Product::all();
            $purchaseorder = Purchaseorder::where([['isDelivery',1],['paid','!=',0],['cradit',0]])->whereBetween('paidDate',[$start,$end])->get();
            return view('admin.report.paymentSearch',compact('product','purchaseorder'));
        }elseif($start==0 || $end==0){//search by user ID
            $cutomerContact = Customer::where('id',$custName)->value('contactNo');
            $user_id = User::where('contactNum',$cutomerContact)->value('id');
            $product = Product::all();
            if($user_id!=""){
                $purchaseorder = Purchaseorder::where('user_id',$user_id)->where([['isDelivery',1],['paid','!=',0],['cradit',0]])->get();
                return view('admin.report.paymentSearch',compact('product','purchaseorder'));
            }else{
                $purchaseorder = Purchaseorder::where('customer_id',$custName)->where([['isDelivery',1],['paid','!=',0],['cradit',0]])->get();
                return view('admin.report.paymentSearch',compact('product','purchaseorder'));
            }

        }elseif($start!=0 && $end!=0 && $custName!=0){
            $cutomerContact = Customer::where('id',$custName)->value('contactNo');
            $user_id = User::where('contactNum',$cutomerContact)->value('id');
            $product = Product::all();
            if($user_id!=""){
                $purchaseorder = Purchaseorder::where('user_id',$user_id)->where([['isDelivery',1],['paid','!=',0],['cradit',0]])->whereBetween('paidDate',[$start,$end])->get();
                return view('admin.report.paymentSearch',compact('product','purchaseorder'));
            }else{
                $purchaseorder = Purchaseorder::where('customer_id',$custName)->where([['isDelivery',1],['paid','!=',0],['cradit',0]])->whereBetween('paidDate',[$start,$end])->get();
                return view('admin.report.paymentSearch',compact('product','purchaseorder'));
            }

        }
    }

    public function customerCredit(){

        $customer = Customer::all();
//            DB::table('positions')->join('users','users.position_id','=','positions.id')->select('users.nameDisplay','users.id')->where('positions.name','sale')->orwhere('positions.name','sd')->get();
        $product = Product::all();
        $purchaseorder = Purchaseorder::where([['isDelivery',1],['cradit','!=',0]])->get();
        return view('admin.report.customerCredit',compact('product','purchaseorder','customer'));
    }

    public function customerCreditSearch($cusName, $startDate, $endDate){
//        customerCreditSearch

        $start=$startDate;
        $end=$endDate;
        if($start==0 && $end==0 && $cusName==0){
            $product = Product::all();
            $purchaseorder = Purchaseorder::where([['isDelivery',1],['cradit','!=',0]])->get();
            return view('admin.report.customerCreditSearch',compact('product','purchaseorder'));
        }

        if($startDate && $endDate !=0){
            $start= Carbon::parse($startDate)->format('Y-m-d');
            $end= Carbon::parse($endDate)->format('Y-m-d');
        }

        if($cusName==0){//Search between
            $product = Product::all();
            $purchaseorder = Purchaseorder::where([['isDelivery',1],['cradit','!=',0]])->whereBetween('dueDate',[$start,$end])->get();
            return view('admin.report.customerCreditSearch',compact('product','purchaseorder'));
        }elseif($start==0 || $end==0){//search by user ID
            $cutomerContact = Customer::where('id',$cusName)->value('contactNo');
            $user_id = User::where('contactNum',$cutomerContact)->value('id');
            $product = Product::all();
            if($user_id!=""){
                $purchaseorder = Purchaseorder::where('user_id',$user_id)->where([['isDelivery',1],['cradit','!=',0]])->get();
                return view('admin.report.customerCreditSearch',compact('product','purchaseorder'));
            }else{
                $purchaseorder = Purchaseorder::where('customer_id',$cusName)->where([['isDelivery',1],['cradit','!=',0]])->get();
                return view('admin.report.customerCreditSearch',compact('product','purchaseorder'));
            }

        }elseif($start!=0 && $end!=0 && $cusName!=0){
            $cutomerContact = Customer::where('id',$cusName)->value('contactNo');
            $user_id = User::where('contactNum',$cutomerContact)->value('id');
            $product = Product::all();
            if($user_id!=""){
                $purchaseorder = Purchaseorder::where('user_id',$user_id)->where([['isDelivery',1],['cradit','!=',0]])->whereBetween('dueDate',[$start,$end])->get();
                return view('admin.report.customerCreditSearch',compact('product','purchaseorder'));
            }else{
                $purchaseorder = Purchaseorder::where('customer_id',$cusName)->where([['isDelivery',1],['cradit','!=',0]])->whereBetween('dueDate',[$start,$end])->get();
                return view('admin.report.customerCreditSearch',compact('product','purchaseorder'));
            }

        }
    }

}
