<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use App\Customer;
use App\User;
use App\Province;
use App\District;
use App\Commune;
use App\Village;
use Auth;
use Carbon\Carbon;
use App\Channel;
use App\Brand;
use Illuminate\Support\Facades\Input;
//use vendor\symfony\console\Input\input;

class customerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Customer::all();
        $provinces = Province::pluck('name','id')->all();
        $districts = District::pluck('name','id')->all();
        $communes = Commune::pluck('name','id')->all();
        $villages = Village::pluck('name','id')->all();
        $channels = Channel::pluck('name','id')->all();
        if(Auth::user()->position->name =='SD'){
            $brandid = Auth::user()->brand->id;
            $customers = Customer::where('brand_id','=',$brandid)->get();
            return view('admin.customerSD.index',compact('customers','channels'));
        }else{
             return view('admin.customer.index',compact('customer','provinces','districts','communes','villages','channels'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $provinces = Province::pluck('name','id')->all();
        $districts = District::pluck('name','id')->all();
        $communes = Commune::pluck('name','id')->all();
        $villages = Village::pluck('name','id')->all();
        $channels = Channel::pluck('name','id')->all();
        if(Auth::user()->position->name =='SD'){
            return view('admin.customerSD.create',compact('channels'));
        }else{
            return view('admin.customer.create',compact('provinces','districts','communes','villages','channels'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'contactNo' => 'required|string|min:8',
        ]);
            $customer = new Customer;
            $customer->user_id = Auth::user()->id;
            $customer->name = Input::get("name");
            $customer->contactNo = Input::get("contactNo");
            $customer->homeNo = Input::get("homeNo");
            $customer->streetNo = Input::get("streetNo");
            $customer->location = Input::get("location");
            $customer->channel_id = Input::get("channel_id");
            $customer->village_id = Input::get("village_id");
            $customer->district_id = Input::get("district_id");
            $customer->commune_id = Input::get("commune_id");
            $customer->province_id = Input::get("province_id");
            if(Auth::user()->position->name =='SD'){
                $brandid = User::where('id','=',Auth::user()->id)->value('brand_id');
                $customer->brand_id = $brandid;
            }
            $customer->save();
            if(Auth::user()->position->name =='Sale'){
            	return redirect()->route('purchaseOrders.create')->with('message','This new customer has been created successfully!');
            }else{
            	return redirect()->route('customers.index')->with('message','This new customer has been created successfully!');
            }
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
         $customer = Customer::findOrFail($id);
         $provinces = Province::pluck('name','id')->all();
         $districts = District::pluck('name','id')->all();
         $communes = Commune::pluck('name','id')->all();
         $villages = Village::pluck('name','id')->all();
         $channels = Channel::pluck('name','id')->all();
          if(Auth::user()->position->name =='SD'){
            return view('admin.customerSD.edit',compact('customer','channels'));
        }else{
            return view('admin.customer.edit',compact('customer','provinces','districts','communes','villages','channels'));
        }
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
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);
            $customer = Customer::findOrFail($id);
            $customer->user_id = Auth::user()->id;
            $customer->name = Input::get("name");
            $customer->contactNo = Input::get("contactNo");
            $customer->homeNo = Input::get("homeNo");
            $customer->streetNo = Input::get("streetNo");
            $customer->location = Input::get("location");
            $customer->channel_id = Input::get("channel_id");
            $customer->village_id = Input::get("village_id");
            $customer->district_id = Input::get("district_id");
            $customer->commune_id = Input::get("commune_id");
            $customer->province_id = Input::get("province_id");
            if(Auth::user()->position->name =='SD'){
                $brandid = User::where('id','=',Auth::user()->id)->value('brand_id');
                $customer->brand_id = $brandid;
            }
            $customer->save();
            return redirect()->route('customers.index')->with('message','This customer has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('customers.index')->with('message','This customer has been deleted successfully!');
    }
}
