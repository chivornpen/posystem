<?php

namespace App\Http\Controllers;

use App\Exchange;
use App\Returnpro;
use App\Stockout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Purchaseorder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Auth;
use Carbon\Carbon;
use App\SetValue;
use App\User;
use App\Position;
use App\Customer;
use App\Pricelist;
use App\Chartaccount;
use App\Transection;
use App\Tmppayment;
use App\Setvariable;
class InvoicePOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = 2;
        $pos = PurchaseOrder::where('isGenerate','=',0)->get();
        $paids = PurchaseOrder::where('isPayment','=',1)->get();
        $cradits = PurchaseOrder::where('isPayment','=',0)->get();
        return view('admin.invoicePO.index',compact('pos','paids','cradits','id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $exchange = Exchange::all();
        return view('admin.invoicePO.exchangeInvoice',compact('exchange'));
    }

    public function createPayment(Request $re)
    {
        $setVariable = $re->cookie('setVariablePayment');
        if(count($setVariable)){
            $purchaseorders = Purchaseorder::where('isPayment','=',0)->get();
           return view('admin.invoicePO.paymentform',compact('purchaseorders'));
        }else{
            $chartaccounts = Chartaccount::whereIn('typeaccount_id',[1,5,6])->pluck('description','id');
            return view('admin.setvariable.create',compact('chartaccounts'));
        }
    }
    public function viewincomepayment()
    {
        $transections = Transection::select('batchID')->distinct('batchID')->get();
        return view('admin.invoicePO.viewincomepayment',compact('transections'));
    }
    public function entryPayment($id)
    {
        $cradit = Purchaseorder::where('id','=',$id)->value('cradit');
        return view('admin.invoicePO.viewtextboxpayment',compact('cradit'));
    }

    //show invoice have to return to customer
    public function ProductReturn(){
        $productReturn = Returnpro::where('isGenerate','=',0)->get();
        return view('admin.invoicePO.invoiceReturnProductCreate',compact('productReturn'));
    }

    public function view($id){//View invoice exchange by
        if($id){
            $ex = Exchange::findOrFail($id);
            $view = $ex->products;
        }else{
            $view=false;
        }
        return view('admin.invoicePO.viewExchangeInvoice',compact('view','id'));
    }
    //show conten invoice return when chose in drop down
    public function showContentInvoiceReturn($returnId,$status){

        if($returnId!=0){
            $returnpro = DB::table('product_returnpro')->where('returnpro_id',$returnId)->selectRaw('product_id, sum(qtyreturn) as QR, sum(qtyorder) as QO, sum(qtyreturn)+sum(qtyorder) as TQ')->groupBy('product_id')->get();
        }else{
            $returnpro=false;
        }
        return view('admin.invoicePO.viewProReturnInvoice',compact('returnpro','status','returnId'));
    }

    //create Invoice Product Return
    public function ProductReturnInvoice($returnId,$status){
        $productId = 0;
        $quantities = 0;
        $unitprice = 0;
        $amount = 0;
        $discount = 0;
        $cod = 0;
        $now = Carbon::now()->toDateString();
        $returnPro = Returnpro::where('id',$returnId)->value('stockout_id');
        $purchaseOrderId = Stockout::where('id',$returnPro)->value('purchaseorder_id');

        $purchaseorder = Purchaseorder::findOrFail($purchaseOrderId);
        $user_id= $purchaseorder->user_id;
        $customer_id= $purchaseorder->customer_id;
        $discount= $purchaseorder->discount;
        $cod = $purchaseorder->cod;

        $purchaseorder = new Purchaseorder();
        $purchaseorder->poDate= $now;
        $purchaseorder->dueDate= $now;
        $purchaseorder->paidDate= $now;
        $purchaseorder->invoiceDate= $now;
        $purchaseorder->totalAmount= 0;
        $purchaseorder->discount= $discount;
        $purchaseorder->vat= 0;
        $purchaseorder->diposit= 0;
        $purchaseorder->user_id= $user_id;
        $purchaseorder->printedBy= 0;
        $purchaseorder->customer_id= $customer_id;
        $purchaseorder->cod= $cod;
        $purchaseorder->rate= 0;
        $purchaseorder->isGenerate= 0;
        $purchaseorder->isPayment= 1;
        $purchaseorder->paid= 0;
        $purchaseorder->cradit= 0;
        $purchaseorder->isDelivery= 1;
        if($status==1){
            $purchaseorder->status= "comp";//company paid
        }else{
            $purchaseorder->status= "cusp";//customer paid
        }
        $purchaseorder->save();
        $purchaseorderId = $purchaseorder->id;

        $returnpro = DB::table('product_returnpro')->where('returnpro_id',$returnId)->selectRaw('product_id, sum(qtyreturn) as QR, sum(qtyorder) as QO, sum(qtyreturn)+sum(qtyorder) as TQ')->groupBy('product_id')->get();

        foreach ($returnpro as $p){
//            echo $purchaseOrderId."<br>";
            $unitprice= DB::table('purchaseorder_product')->where([['purchaseorder_id','=',$purchaseOrderId],['product_id','=',$p->product_id],])->value('unitPrice');
               $product_id = $p->product_id;
                if($status==1){//company paid
                    $quantities= $p->QR;
//                  echo "$amount =$amount+($unitprice*$quantities)"."<br>";
                    $pAmount=($unitprice*$quantities);
                    $amount =$amount+($unitprice*$quantities);
                    $purchaseorder->products()->attach($product_id,['qty'=>$quantities,'unitPrice'=>$unitprice,'amount'=>$pAmount,'user_id'=>$user_id]);
                }elseif($status==2){//customer paid
                    $quantities= $p->QO;
//                  echo "$amount =$amount+($unitprice*$quantities)"."<br>";
                    $pAmount=($unitprice*$quantities);
                    $amount =$amount+($unitprice*$quantities);
                    $purchaseorder->products()->attach($product_id,['qty'=>$quantities,'unitPrice'=>$unitprice,'amount'=>$pAmount,'user_id'=>$user_id]);
                }
        }

        $grandTotal = $amount-($amount*$discount/100);
        $paid = $grandTotal-($grandTotal*$cod/100);

        $purchaseorderUpdate = Purchaseorder::findOrFail($purchaseorderId);
        $purchaseorderUpdate->totalAmount= $amount;
        $purchaseorderUpdate->paid= round($paid,2,PHP_ROUND_HALF_UP);
        $purchaseorderUpdate->save();

        $returnProduct = Returnpro::findOrFail($returnId);
        $returnProduct->isGenerate=1;
        $returnProduct->purchaseorder_id = $purchaseorderId;
        $returnProduct->save();

        $stockout = Stockout::findOrFail($returnPro);
        $stockout->status=1;
        $stockout->save();

    }

    public function createXchangeInvoice($id){

           if($id){
               $user_id =0;
               $customer_id=0;
               $now = Carbon::now()->toDateString();
               $result = DB::table('exchange_product')->selectRaw('product_id, sum(qty) as total')->where('exchange_id','=',$id)->groupBy('product_id')->get();
               $exchange = Exchange::find($id);
               $stockoutID=$exchange->stockout->id;
               $stockout = Stockout::find($stockoutID);
               $purchaseorderID=$stockout->purchaseorder_id;
               $purchaseorder = Purchaseorder::find($purchaseorderID);
               $user_id= $purchaseorder->user_id;
               $customer_id= $purchaseorder->customer_id;

               $purchaseorder = new Purchaseorder();
               $purchaseorder->poDate= $now;
               $purchaseorder->dueDate= $now;
               $purchaseorder->paidDate= $now;
               $purchaseorder->invoiceDate= $now;
               $purchaseorder->totalAmount= 0;
               $purchaseorder->discount= 0;
               $purchaseorder->vat= 0;
               $purchaseorder->diposit= 0;
               $purchaseorder->user_id= $user_id;
               $purchaseorder->printedBy= 0;
               $purchaseorder->customer_id= $customer_id;
               $purchaseorder->cod= 0;
               $purchaseorder->rate= 0;
               $purchaseorder->isGenerate= 0;
               $purchaseorder->isPayment= 1;
               $purchaseorder->paid= 0;
               $purchaseorder->cradit= 0;
               $purchaseorder->isDelivery= 0;
               $purchaseorder->status= "ex";
               $purchaseorder->save();
               $purchaseorderId = $purchaseorder->id;
                       foreach ($result as $re){
                           $purchaseorder->products()->attach($re->product_id,['qty'=>$re->total,'unitPrice'=>0,'amount'=>0,'user_id'=>$user_id]);
                       }
               $exchange->purchaseorder_id=$purchaseorderId;
               $exchange->save();
               $stockout->status=1;
               $stockout->save();

               return "<div style='color: #0d6aad; margin-left: 10px;'>created successfully...</div>";
           }
    }


    public function store()
    {

    }


    public function show($id)
    {
        $details = Purchaseorder::findOrFail($id);
        $totalAmount = Purchaseorder::where('id','=',$id)->value('totalAmount');
        $discount = Purchaseorder::where('id','=',$id)->value('discount');
        $cod = Purchaseorder::where('id','=',$id)->value('cod');
        $vat = Purchaseorder::where('id','=',$id)->value('vat');
        $diposit = Purchaseorder::where('id','=',$id)->value('diposit');
        $Vtotal = $totalAmount  - $totalAmount * $discount /100;
        $Vcod =$Vtotal * $cod /100;
        $Vvat = $totalAmount * $vat/100;
        $grandTotal = $Vtotal - $Vcod + $Vvat;
        $VgrandTotal = $grandTotal - $diposit;
         return view('admin.invoicePO.showPoDetails',compact('details','totalAmount','discount','cod','vat','diposit','VgrandTotal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
            $po = Purchaseorder::findOrFail($id);
            if(Input::get("vat")==1){
                $vat = SetValue::where('id','=',11)->where('status','=',1)->value('value');
                $po->vat = $vat;
            }else{
                $po->vat =0;
            }
            if(Input::get("diposit")!=''){
                $po->diposit = Input::get("diposit");
            }else{
                $po->diposit =0;
            }
            if(Input::get("rate")!=''){
                $po->rate= Input::get("rate");
            }else{
                $po->rate=0;
            }
            $po->printedBy = Auth::user()->id; 
            $po->save();
           return redirect()->route('invoicePO.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $po = Purchaseorder::findOrFail($id);
        $positionid = Position::where('name','=','Account')->value('id');
        $userName = User::where('position_id','=',$positionid)->value('id');
        $po->printedBy = Auth::user()->id; 
        $po->save(); 
        $totalAmount = Purchaseorder::where('id','=',$id)->value('totalAmount');
        $discount = Purchaseorder::where('id','=',$id)->value('discount');
        $cod = Purchaseorder::where('id','=',$id)->value('cod');
        $vat = Purchaseorder::where('id','=',$id)->value('vat');
        $diposit = Purchaseorder::where('id','=',$id)->value('diposit');
        $rate = Purchaseorder::where('id','=',$id)->value('rate');
        $Vtotal = $totalAmount  - $totalAmount * $discount /100;
        $Vcod =$Vtotal * $cod /100;
        $Vvat = $totalAmount * $vat/100;
        $grandTotal = $Vtotal - $Vcod + $Vvat;
        $VgrandTotal = $grandTotal - $diposit;
        $VgrandTotalk = $VgrandTotal * $rate;
        $VgrandTotalkh= (round($VgrandTotalk,0,PHP_ROUND_HALF_UP));
        
        if(substr($VgrandTotalkh, -2,2)>0){
                    $round = 100-substr($VgrandTotalkh, -2,2);
                    $totalAmountkh = $VgrandTotalkh+$round;
                }else
                {
                    $totalAmountkh = $VgrandTotalkh;
                }
        $printedBy = Purchaseorder::where('id','=',$id)->value('printedBy');
        $createdInv = User::where('id','=',$printedBy)->value('nameDisplay');
        $sex = User::where('id','=',$printedBy)->value('sex');
        $customerid = Purchaseorder::where('id','=',$id)->value('customer_id');
        //get customer id by user id
        $userid = Purchaseorder::where('id','=',$id)->value('user_id');
        if($customerid == null){
            $phone = User::where('id','=',$userid)->value('contactNum');
            $sdid = Customer::where('contactNo','=',$phone)->value('id');
        }
        //--------
        return view('admin.invoicePO.invoice',compact('po','totalAmount','discount','cod','vat','diposit','Vcod','Vvat','VgrandTotal','createdInv','sex','rate','totalAmountkh','sdid'));
    }
    public function getPopupEditPO($id)
    {
        $po = Purchaseorder::findOrFail($id);
        return view('include.editPO',compact('po'));
    }
    public function getPopupEditCradit($id)
    {
        $cradit = Purchaseorder::findOrFail($id);
        return view('include.editPopupCradit',compact('cradit'));
    }
    public function updateGenerate($id){
        $update = Purchaseorder::where('id','=', $id)->first();
        $update->isGenerate = 1;
        $update->invoiceDate = Carbon::now()->toDateString();
        $update->save();
    }
    public function showInvoicePaid($id){
        $id =$id;
        $pos = PurchaseOrder::where('isGenerate','=',0)->get();
        $paids = PurchaseOrder::where('isPayment','=',1)->get();
        $cradits = PurchaseOrder::where('isPayment','=',0)->get();
        return view('admin.invoicePO.index',compact('pos','paids','cradits','id'));
    }
    public function submit(Request $request)
    {
        $this->validate($request, [
            'invN' => 'required',
            'paid' => 'required',
            'cur'  => 'required',
        ]);
        $batchId=0;
        $bacth = Transection::OrderBy('batchID','desc')->value('batchID');
        if ($bacth) {
            $batchId = $bacth;
        }else{
            $batchId = 0;
        }
        $rate = Input::get('exchangerate');
        $currency = Input::get('cur');

        $invoice = Input::get('invN');

        $purchase = Purchaseorder::findOrFail($invoice);
        $cod = $purchase->cod;
        $discount = $purchase->discount;
        $grandTotal = $purchase->cradit;
        $paid = $purchase->paid;
        $paidnew = Input::get("paid");
        $cradit = $grandTotal - $paidnew;
            if($cradit==0){

                $num = Tmppayment::where('purchaseorder_id','=',$invoice)->count();
                if ($num) {
                    $newrate = Input::get('exchangerate');
                    $oldrate = Tmppayment::where('purchaseorder_id','=',$invoice)->sum('exchangerate');
                    $allrate = $newrate+$oldrate;
                    $numrate = $num +1;
                    $rate = $allrate/$numrate;
                }
                $setvariables = $re->cookie('setVariablePayment');
                        $revenueid = 0;$revenuedr = 0;$revenuecr = 0;$cohid = 0;$cohdr = 0;$cohcr = 0;$cogsid = 0;$cogsdr = 0;$cogscr = 0; $re_typeaccount_id = 0; $coh_typeaccount_id = 0; $cogs_typeaccount_id = 0;
                if(count($setvariables)){
                    foreach ($setvariables as $value) {
                        $revenueid = $value['revenue']['id'];
                        $revenuedr = $value['revenue']['Dr'];
                        $revenuecr = $value['revenue']['Cr'];
                        $re_typeaccount_id = $value['revenue']['typeaccount_id'];
                        $cohid = $value['COH']['id'];
                        $cohdr = $value['COH']['Dr'];
                        $cohcr = $value['COH']['Cr'];
                        $coh_typeaccount_id = $value['COH']['typeaccount_id'];
                        $cogsid = $value['COGS']['id'];
                        $cogsdr = $value['COGS']['Dr'];
                        $cogscr = $value['COGS']['Cr'];
                        $cogs_typeaccount_id = $value['COGS']['typeaccount_id'];
                    }                   
                }
                $purchaseDetails = DB::table('purchaseorder_product')->where('purchaseorder_id',$invoice)->select('product_id',DB::raw('SUM(qty) as qty'),DB::raw('sum(unitPrice) as price'))->groupBy('product_id')->get();
                foreach ($purchaseDetails as $poDetail) {
                    $product_id = $poDetail->product_id;
                    $qty = $poDetail->qty;
                    $price = $poDetail->price;
                    $now = Carbon::now()->toDateString();
                    $landingprice = DB::table('pricelists')->where([['product_id','=',$product_id],['startdate','<=',$now],['enddate','>=',$now],])->value('landingprice');
                    $cash = ($qty * $price)-(($qty * $price)*($discount/100));
                    $cashOnHand = $cash-$cash*($cod/100);
                    $cost = $landingprice * $qty;
                    $revenue = $cashOnHand - $cost;

                    $runningCash = Transection::where('chartaccount_id',1)->OrderBy('id','desc')->value('runningBalance');
                    $transection = Transection::create(array(
                        'batchID' => $batchId+1,
                        'transectionDate'=>Carbon::now()->toDateString(),
                        'chartaccount_id'=>$cohid,
                        'typeaccount_id'=>$coh_typeaccount_id,
                        'drAmt'=>$cashOnHand*$cohdr,
                        'crAmt'=>$cohcr*0,
                        'runningBalance'=>$cashOnHand+$runningCash,
                        'Postamount'=>$cashOnHand*$rate,
                        'currency'=>$currency,
                        'exchangeRate'=>$rate,
                        'user_id'=>Auth::user()->id,
                    )); 

                    $runningCost = Transection::where('chartaccount_id',8)->OrderBy('id','desc')->value('runningBalance');

                    $transection = Transection::create(array(
                        'batchID' => $batchId+1,
                        'transectionDate'=>Carbon::now()->toDateString(),
                        'chartaccount_id'=>$cogsid,
                        'typeaccount_id'=>$cogs_typeaccount_id,
                        'drAmt'=>$cogsdr*0,
                        'crAmt'=>$cost*$cogscr,
                        'runningBalance'=>$runningCost-$cost,
                        'Postamount'=>-$cost*$rate,
                        'currency'=>$currency,
                        'exchangeRate'=>$rate,
                        'user_id'=>Auth::user()->id,
                    ));

                    $runningRe = Transection::where('chartaccount_id',9)->OrderBy('id','desc')->value('runningBalance');

                    $transection = Transection::create(array(
                        'batchID' => $batchId+1,
                        'transectionDate'=>Carbon::now()->toDateString(),
                        'chartaccount_id'=>$revenueid,
                        'typeaccount_id'=>$re_typeaccount_id,
                        'drAmt'=>$revenuedr*0,
                        'crAmt'=>$revenue*$revenuecr,
                        'runningBalance'=>$runningRe-$revenue,
                        'Postamount'=>$revenue*$rate,
                        'currency'=>$currency,
                        'exchangeRate'=>$rate,
                        'user_id'=>Auth::user()->id,
                    ));
                }
                $paids = $paid + $paidnew;
                $purchase->paid = $paids;
                $purchase->cradit = $cradit;
                $purchase->isPayment = 1;
                $purchase->paidDate = Carbon::now()->toDateString();
                $purchase->printedBy = Auth::user()->id;   
                $purchase->save(); 
                $tmps = Tmppayment::where('purchaseorder_id','=',$invoice)->get();
                    foreach ($tmps as $tmp) {
                        $tmp->delete();
                    }
            }else{
                $tmppayment = new Tmppayment;
                $tmppayment->purchaseorder_id = $invoice;
                $tmppayment->cradit = $cradit;
                $tmppayment->paid = $paidnew;
                $tmppayment->exchangerate = $rate;
                $tmppayment->user_id = Auth::user()->id;
                $tmppayment->save();
                $paids = $paid + $paidnew;
                $purchase->paid = $paids;
                $purchase->cradit = $cradit;
                $purchase->isPayment = 0;
                $purchase->paidDate = Carbon::now()->toDateString();
                $purchase->save();
            }
        return $this->viewincomepayment();
    } 

    public function incomeStatement()
    {
        $transections =array();
        //$transections = Transection::whereIn('typeaccount_id',[5,6])->get();
        return view('admin.invoicePO.incomestatement',compact('transections'));
    } 
  
    public function incomeReportFilter(Request $request){
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
        }else if($request->input('typeDate')=="y"){
            $endDate = Carbon::parse($request->input('date'))->toDateString();
            $startDate = substr(Carbon::parse($request->input('date'))->toDateString(), 0, -5)."01"."-01";
            $reportType ="Yearly";
        }
        $transections = Transection::whereIn('typeaccount_id',[6,5])->whereBetween('transectionDate',[$startDate,$endDate])->OrderBy('chartaccount_id','desc')->get();
        return view('admin.invoicePO.incomestatement',compact('transections','endDate','reportType'));
    } 
}
