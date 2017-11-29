<?php

namespace App\Http\Controllers;

use App\Chartaccount;
use App\Transection;
use App\Typeaccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Accounting extends Controller
{
    public function index(){
        $typeacc = Typeaccount::pluck('description','id')->all();
//        $chartacc = Chartaccount::pluck('description','id')->all();
        $view = Transection::where('batchID',Session::get('Batch'))->get();
        $totalDr = Transection::where('batchID',Session::get('Batch'))->sum('drAmt');
        $totalCr = Transection::where('batchID',Session::get('Batch'))->sum('crAmt');
        return view('admin.account.create',compact('typeacc','view','totalDr','totalCr'));
    }

    public function CreateAccType(){
        $accountType = Typeaccount::all();
        $user_id = Auth::user()->id;
        if(!$accountType->count()){
            $data=array([
                    'typeaccountcode'=>10,
                    'description'=>'Current Asset',
                    'user_id'=>$user_id
                ],
                [
                    'typeaccountcode'=>20,
                    'description'=>'Fix Asset',
                    'user_id'=>$user_id
                ],[
                    'typeaccountcode'=>30,
                    'description'=>'Liabilities',
                    'user_id'=>$user_id
                ],[
                    'typeaccountcode'=>40,
                    'description'=>'Equities',
                    'user_id'=>$user_id
                ],[
                    'typeaccountcode'=>50,
                    'description'=>'Revenues',
                    'user_id'=>$user_id
                ],[
                    'typeaccountcode'=>60,
                    'description'=>'Expenses',
                    'user_id'=>$user_id
                ],[
                    'typeaccountcode'=>70,
                    'description'=>'Investment',
                    'user_id'=>$user_id
                ],[
                    'typeaccountcode'=>80,
                    'description'=>'Dividend',
                    'user_id'=>$user_id
                ]
            );
            foreach ($data as $d){
                $accTypeCreate = new Typeaccount();
                $accTypeCreate->typeaccountcode=$d['typeaccountcode'];
                $accTypeCreate->description=$d['description'];
                $accTypeCreate->user_id=$d['user_id'];
                $accTypeCreate->save();
            }
        }
        $accountType = Typeaccount::all();
        return view('admin.account.AccTypeCreate',compact('accountType'));

    }
//    public function storedAccType(Request $request){
//
//        $this->validate($request,[
//            'TypeAccountCode'=>'required|numeric',
//            'des'=>'required'
//        ],[
//            'TypeAccountCode.required'=>'account type code required..',
//            'description.required'=>'Description required...',
//        ]);
//        $accountType = new Typeaccount();
//        $accountType->typeaccountcode = $request->input('TypeAccountCode');
//        $accountType->description = trim($request->input('des'));
//        $accountType->user_id = Auth::user()->id;
//        $accountType->save();
//        return redirect('account/create/acc/type');
//
//    }

    public function CreateAccChart(){

        $Chartaccount = Chartaccount::OrderBy('accountcode','Asc')->get();
        $typeacc = Typeaccount::pluck('description','id')->all();
        return view('admin.account.ChartAccCreate',compact('Chartaccount','typeacc'));
    }

    public function storedAccChart(Request $request){
        $this->validate($request,[
            'typeaccountcode'           =>'required',
            'sign'                      =>'required',
            'valueSign'                 =>'required',
            'description'               =>'required'
        ], [
            'typeaccountcode.required'  =>'Please select account type',
            'description.required'      =>'Description required',
            'sign.required'             =>'The field sign required',
            'valueSign.required'        =>'Please choose one value'

            ]);
        $accType_id = $request->input('typeaccountcode');
        $des = trim($request->input('description'));
        $accountTypeCode = Typeaccount::where('id',$accType_id)->value('typeaccountcode');
        $generate=0;
        $query = Chartaccount::where('typeaccount_id',$accType_id)->get();
        if(count($query)){
            $accCode = Chartaccount::where('typeaccount_id',$accType_id)->orderBy('accountcode','desc')->value('accountcode');
            $generate = substr($accCode,2)+1 ;
        }else{
            $generate=1;
        }
        $accountCode=$accountTypeCode.sprintf('%04d',$generate);
        $chartAcc = new Chartaccount();
        $chartAcc->accountcode = $accountCode;
        $chartAcc->typeaccount_id =$accType_id;
        $chartAcc->description =$des;
        $dr =0;
        $cr =0;
        if($request->input('sign') == "dr"){
            if($request->input('valueSign')> 0){
                $dr = 1;
                $cr=-1;
            }else{
                $dr = -1;
                $cr=1;
            }
        }else{
            if($request->input('valueSign')>0){
                $dr = -1;
                $cr=1;
            }else{
                $dr = 1;
                $cr= -1;
            }
        }
        $chartAcc->drsign = $dr;
        $chartAcc->crsign = $cr;

        $chartAcc->user_id=Auth::user()->id;

        $chartAcc->save();
        return redirect('/account/create/acc/chart');
    }

    public function delete($id,$Model_name){

        if($Model_name =="Chartaccount"){
            Chartaccount::where('id',$id)->delete();
            return redirect('/account/create/acc/chart');
        }else{
            Typeaccount::where('id',$id)->delete();
            return redirect('account/create/acc/type');
        }

    }

    public function update(Request $request,$id){
        if($request->input('modalName')=="AccountType"){
            $this->validate($request,[
                'typeaccountcode'=>'required',
                'description'=>'required'
            ]);
            $AccType = Typeaccount::find($id);
            $AccType->typeaccountcode = trim($request->input('typeaccountcode'));
            $AccType->description = trim($request->input('description'));
            $AccType->user_id = Auth::user()->id;
            $AccType->save();
            return redirect('account/create/acc/type');
        }else{
            $this->validate($request,[
                'typeaccount_id'=>'required',
                'description'=>'required',
                'drsign'=>'required'
            ]);

            $accountTypeCode = Typeaccount::where('id',$request->input('typeaccount_id'))->value('typeaccountcode');
            $generate=0;
            $query = Chartaccount::where('typeaccount_id',$request->input('typeaccount_id'))->get();
            if(count($query)){
                $accCode = Chartaccount::where('typeaccount_id',$request->input('typeaccount_id'))->OrderBy('id','desc')->value('accountcode');
                $generate = substr($accCode,2)+1 ;
            }else{
                $generate=1;
            }
            $accountCode=$accountTypeCode.sprintf('%04d',$generate);
            $drsign = $request->input('drsign');
            $crsign =0;
            if($drsign>0){
                $drsign = 1;
                $crsign = -1;
            }else{
                $drsign = -1;
                $crsign = 1;
            }
            if($drsign!=0 && $drsign<2 || $drsign>-2){
                $AccChart = Chartaccount::find($id);
                $AccChart->accountcode = $accountCode;
                $AccChart->typeaccount_id = trim($request->input('typeaccount_id'));
                $AccChart->description = trim($request->input('description'));
                $AccChart->drsign=$drsign;
                $AccChart->crsign=$crsign;
                $AccChart->user_id = Auth::user()->id;
                $AccChart->save();
                return redirect('/account/create/acc/chart');
            }
        }

    }


    public function selectChartAcc($id){
        $chartacc = Chartaccount::where('typeaccount_id',$id)->select('description','id','accountcode')->get();
        return response()->json($chartacc);

    }
    public function getSign($id){
        $getSign= Chartaccount::where('id',$id)->select('drsign','crsign')->get();
        return response()->json($getSign);
    }

    public function create(Request $request){//Stored record temporary in array

        $this->validate($request,[
            'account_type'  =>'required',
            'chart_account' =>'required',
            'sign'          =>'required',
            'amount'        =>'required|min:1|numeric',
            'currency'      =>'required',
            'exchange_rate' =>'required',
//            'brand_code'    =>'required',
            'description'   =>'required'
        ],[
            'account_type.required'     =>'Account type required...',
            'chart_account.required'    =>'Chart of account required...',
            'sign.required'             =>'Please choose sign',
            'amount.required'           =>'The amount required...',
            'amount.min'                =>'The amount must be at least 1.',
            'currency.required'         =>'Please choose currency...',
            'exchange_rate.required'    =>'Please provide exchange rate...',
//            'brand_code.required'       =>'The brand code required...',
            'description.required'      =>'Please provide description..'
        ]);
          $generatBatch=0;
          $generateTransition=0;
        if(count(Session::get('Batch'))){
            $generatBatch= Session::get('Batch');
            $T = Transection::OrderBy('transectionCode','desc')->value('transectionCode');
            $generateTransition = $T+1;

        }else{
//            echo "have no session";
            $Batch=0;
            $Tran = 0;
            $B = Transection::OrderBy('batchID','desc')->value('batchID');
            if($B){
                $Batch = $B;
            }else{
                $Batch=0;
                $Tran =1;
            }
            $generatBatch = $Batch+1;
            $generateTransition=$Tran;
            Session::put('Batch',$generatBatch);
        }
////    Session::forget('Batch');  Delete session
///     Session::forget('Transition');  Delete session

        $runningB = Transection::where('chartaccount_id',$request->input('chart_account'))->OrderBy('id','desc')->value('runningBalance');
        $RB =0;
        $Amount=0;
        $amount =$request->input('amount');
        $transition = new Transection();
        $transition->batchID = $generatBatch;
        $transition->transectionDate = Carbon::now()->toDateString();

        $transition->transectionCode =$generateTransition;
        $transition->chartaccount_id = $request->input('chart_account');
        $transition->typeaccount_id = $request->input('account_type');

        $drsign =$request->input('drsign');
        $crsign =$request->input('crsign');

        if($request->input('sign')=="crsign"){//credit
            $transition->drAmt = $drsign*0;
            $Amount = $crsign*$amount;
            $transition->crAmt = $crsign*$amount;
        }else{
            $Amount= $drsign*$amount;
            $transition->drAmt =$drsign*$amount;
            $transition->crAmt = $crsign*0;
        }
        if ($runningB){
              $RB = $runningB + $Amount;
        }else{
              $RB = $Amount;
        }
        $transition->runningBalance = $RB;
        $transition->Postamount=$Amount*$request->input('exchange_rate');
        $transition->currency = $request->input('currency');
        $transition->exchangeRate = $request->input('exchange_rate');
        $transition->user_id = Auth::user()->id;
        $transition->save();

      return redirect('account/create/booking');
    }
    public function clearBook(){

        Session::forget('Batch');

        return redirect('account/create/booking');
    }
    public function deleteTransition($id){
        $trans = Transection::find($id);
        $trans->delete();
        return redirect('account/create/booking');
    }



    ///Accounting Report
    public function BanlanceSearch(Request $request){
        $this->validate($request,[
            'date'                =>'required',
            'typeDate'            =>'required'
        ],[
            'date.required'       =>'The date field required',
            'typeDate.required'   =>'Please choose one'
        ]);
        $endDate=null;
        $startDate=null;
        $reportType="";
        if($request->input('typeDate')=="m") {
            $endDate = Carbon::parse($request->input('date'))->toDateString();
            $startDate = substr(Carbon::parse($request->input('date'))->toDateString(), 0, -2) . "01";
            $reportType ="Monthly";
        }else{
            $endDate = Carbon::parse($request->input('date'))->toDateString();
            $startDate = substr(Carbon::parse($request->input('date'))->toDateString(), 0, -5)."01"."-01";
            $reportType ="Yearly";
        }
        $transaction = Transection::whereIn('typeaccount_id',[1,2,3,4])->whereBetween('transectionDate',[$startDate,$endDate])->OrderBy('chartaccount_id','desc')->get();
        return view('admin.accountingReport.BalanceSheet',compact('transaction','endDate','reportType'));
    }
    public function BalanceSheetReport(){
        $transaction=array();
        $endDate=0;
        $reportType="";
//      $transaction = Transection::whereIn('typeaccount_id',[1,2,3,4])->OrderBy('chartaccount_id','desc')->get();
        return view('admin.accountingReport.BalanceSheet',compact('transaction','endDate','reportType'));
    }



    public function trialBalance(){
        $transaction=array();
        $endDate=Carbon::now()->format('F d, Y');
        $reportType="";
        return view('admin.accountingReport.trialBalance',compact('transaction','endDate','reportType'));
    }

    public function trialBalanceFilter(Request $request){
        $this->validate($request,[
            'date'                =>'required',
            'typeDate'            =>'required'
        ],[
            'date.required'       =>'The date field required',
            'typeDate.required'   =>'Please choose one'
        ]);
        $endDate=null;
        $startDate=null;
        $reportType="";
        if($request->input('typeDate')=="m") {
            $endDate = Carbon::parse($request->input('date'))->toDateString();
            $startDate = substr(Carbon::parse($request->input('date'))->toDateString(), 0, -2) . "01";
            $reportType ="Monthly";
        }else{
            $endDate = Carbon::parse($request->input('date'))->toDateString();
            $startDate = substr(Carbon::parse($request->input('date'))->toDateString(), 0, -5)."01"."-01";
            $reportType ="Yearly";
        }
        $data =[];
        $accountType = Typeaccount::where('id','>',0)->select('id')->get();
        foreach ($accountType as $ac){
            $data[] = $ac->id;
        }
        $transaction = Transection::whereIn('typeaccount_id',$data)->whereBetween('transectionDate',[$startDate,$endDate])->OrderBy('chartaccount_id','desc')->get();
        return view('admin.accountingReport.trialBalance',compact('transaction','endDate','reportType'));
    }

}
