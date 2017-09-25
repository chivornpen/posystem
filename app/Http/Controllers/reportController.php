<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Product;
use App\Purchaseorder;
use App\User;
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

}
