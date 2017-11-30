<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Chartaccount;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use App\Setvariable;

class SetvariableController extends Controller
{
    public function create()
    {
    	$setvariables = Setvariable::all();
    	$chartaccounts = Chartaccount::whereIn('typeaccount_id',[1,5,6])->pluck('description','id');
    	return view('admin.setvariable.create',compact('chartaccounts','setvariables'));
    }
    public function save(Request $re)
    {
//    	$this->validate($re, [
//            'chartaccount_id' => 'required',
//            'sign' => 'required',
//            'value' => 'required',
//        ]);
        //Revenue
        $Rcr=0;
        if($re->input('drsign3')==-1){
           $Rcr=1;
        }else{
            $Rcr=-1;
        }
        //Cash on hand
        $Ccr=0;
        if($re->input('drsign1')==-1){
            $Ccr=-1;
        }else{
            $Ccr=1;
        }
        //Cost of good sole
        $COdr=0;
        if($re->input('drsign2')==1){
            $COdr=1;
        }else{
            $COdr=-1;
        }

        $setValue=
        [
	            'VariableName'=>
	            [
	                'Revenue'=>['id'=>$re->input('revenue'),'Dr'=>$re->input('drsign3'),'Cr'=>$Rcr,'typeaccount_id'=>5],
	                'COH'=>['id'=>$re->input('cashonhand'),'Dr'=>1,'Cr'=>$Ccr,'typeaccount_id'=>1],
	                'COGS'=>['id'=>$re->input('cost'),'Dr'=>1,'Cr'=>$COdr,'typeaccount_id'=>6]
            	]

        ];

        if(count($re->cookie('setVariablePayment'))){

        	return $re->cookie('setVariablePayment');

        }else{
        	Cookie::queue(Cookie::make('setVariablePayment',$setValue, time()));
        	return redirect()->back();
        }

		//return $re->cookie('setVariblePayment');
			//Cookie::queue(Cookie::make('setVariblePayment',$setValue, time())
    }
}
