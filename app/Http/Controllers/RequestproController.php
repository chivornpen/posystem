<?php

namespace App\Http\Controllers;

use App\Returnpro;
use App\Stockoutre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Requestpro;
use Carbon\Carbon;
use App\Returnreqpro;

class RequestproController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reqalls = Requestpro::all();
        return view('admin.requestpro.index',compact('reqalls'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::pluck('name','id')->all();
        $requestBy = User::pluck('nameDisplay','id')->all();
        return view('admin.requestpro.create',compact('products','requestBy'));
    }

    public function createverify()
    {
        $reqalls = Requestpro::where('auth_id','=',null)->get();
        return view('admin.requestpro.createverify',compact('reqalls'));
    }
    public function getRequestProduct($id)
    {
        $details = Requestpro::findOrFail($id);
        if ($id) {
            $reqDate = Requestpro::where('id','=',$id)->value('reqDate');
            $reqby = Requestpro::where('id','=',$id)->value('reqby');
            $status = Requestpro::where('id','=',$id)->value('status');
            $username = User::where('id','=',$reqby)->value('nameDisplay');
        }
        return view('admin.requestpro.showverifybyid',compact('details','reqDate','username','status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Input::get('btn_save')){
            $Req = new Requestpro;
            $Req->reqDate = Carbon::now();
            $Req->reqby = Input::get('user_id');  
            $Req->status = Input::get('status');   
            $Req->user_id = Auth::user()->id;       
            $Req->save();
            $tmps = \App\Tmpproreq::where('user_id','=',Auth::user()->id)->get();
                foreach ($tmps as $tmp) {
                $Req->products()->attach($tmp->product_id,
                    ['qty'=>$tmp->qty,
                    'user_id'=>$tmp->user_id]);
                }
            $tmps = \App\Tmpproreq::where('user_id','=',Auth::user()->id)->get();
            foreach ($tmps as $tmp) {
                $tmp->delete();
            }
            $reqalls = Requestpro::all();
            return view('admin.requestpro.index',compact('reqalls'));
        }
    }
    public function discard()
    {
        $tmps = \App\Tmpproreq::where('user_id','=',Auth::user()->id)->get();
            foreach ($tmps as $tmp) {
                $tmp->delete();
            }
            return redirect()->back();
    }

    public function confirm(Request $request)
    {
        $id = Input::get('id');
        $requestpro = Requestpro::findOrFail($id);
        $requestpro->auth_id = Auth::user()->id;
        $requestpro->auth_date = Carbon::now();
        $requestpro->save();
        return redirect()->back();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $details = Requestpro::findOrFail($id);
        return view('admin.requestpro.showPopup',compact('details'));
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
    public function addRequestpro($proid, $qty)
    {
        $user_id = Auth::user()->id;
        $bqtys = DB::select("SELECT qty FROM `tmpproreqs` WHERE product_id = {$proid} AND user_id = {$user_id}");//select qty from brand product
        $oldQty="";
        foreach ($bqtys as $bqty) {
            $oldQty= $bqty->qty;
        }
        if($oldQty!=null){
                $newQty = (int)$qty;
                $qtylast = $oldQty + $newQty;
            DB::table('tmpproreqs')->where([['product_id', '=', $proid], ['user_id', '=', $user_id],])->update(array('qty'=>$qtylast));
        }else{
            DB::table('tmpproreqs')->insert(['product_id' => $proid, 'user_id' => $user_id, 'qty' => $qty]);
        }
        $res = DB::table('tmpproreqs')->where([['user_id','=',$user_id],])->get();
        return response()->json($res);
    }
    public function showProduct(){
    
        $user_id = Auth::user()->id;
        $tmpdata = \App\Tmpproreq::where('user_id',$user_id)->get();
        return view('admin.requestpro.showProduct',compact('tmpdata'));
    }
    public function removeRequestpro($id)
    {
        DB::table('tmpproreqs')->where([['id','=',$id],])->delete();
    }

    //Export request

    public function showRequestToExport(){

        $requstPro = Requestpro::whereNotNull('auth_id')->whereNull('is_export')->get();
        return view('admin.stock_out.showExportRequest',compact('requstPro'));
    }

    public function showRequestDetail($request_id){

        $requstProDetail = Requestpro::where('id',$request_id)->whereNotNull('auth_id')->whereNull('is_export')->get();
        return view('admin.stock_out.showRequestDet',compact('requstProDetail'));
    }

    public function exportRequestPro(Request $request){
        $this->validate($request,[
            'requestNumber'=>'required|min:1'
        ]);
        $qt=0;
        $a= 0;
        $j=0;
        $qtyUpToImport = 0;
        $now = Carbon::now()->toDateString('Y-m-d');
        $resquestpro = Requestpro::find($request->input('requestNumber'));
        if($resquestpro){

            $stockoutre = new Stockoutre();
            $stockoutre->outdate = Carbon::now()->toDateString();
            $stockoutre->requestpro_id = $request->input('requestNumber');
            $stockoutre->user_id = Auth::user()->id;
            $stockoutre->save();
            $stockoutreid = $stockoutre->id;

            foreach ($resquestpro->products as $re){
                $quantitiesRequest=$re->pivot->qty;
                $qt = $quantitiesRequest;
                $productId=$re->id;
                $results = DB::table('import_product')->where([['productId',$productId],['qty','>',0],['expd','>',$now],])->get();
                foreach ($results as $result){
                    $qt = $qt;
                   if($a>=$quantitiesRequest | $result->qty >=$quantitiesRequest){

                       if($j!=$productId){
                           $qtyUpToImport = $result->qty - $quantitiesRequest;
                           if($qtyUpToImport>=0){

                               DB::table('import_product')->where('id', $result->id)->update(array('qty'=>$qtyUpToImport));
                               $prod = Product::findOrFail($productId);//Update qty to main table product
                               $bqty = $prod->qty;
                               $uqty = ($bqty-$quantitiesRequest);
                               $prod->qty = $uqty;
                               $prod->save();

                               DB::table('import_stockoutre')->insert(['stockoutre_id'=>$stockoutreid,'import_id'=>$result->importId, 'product_id' => $productId, 'qty' => $qt,'expd' =>$result->expd]);
                               $j = $productId;
                               $a=0;

                           }
                       }

                   }elseif($result->qty < $quantitiesRequest || $a < $quantitiesRequest){

                       if($j!=$productId){
                           $a = $a + $result->qty;
                           if($a>= $quantitiesRequest){
                               $qtyUpToImport = $result->qty - $qt;
                               DB::table('import_product')->where('id', $result->id)->update(array('qty'=>$qtyUpToImport));
                               $prod = Product::findOrFail($productId);//Update qty to main table product
                               $bqty = $prod->qty;
                               $uqty = ($bqty-$quantitiesRequest);
                               $prod->qty = $uqty;
                               $prod->save();

                               DB::table('import_stockoutre')->insert(['stockoutre_id'=>$stockoutreid,'import_id'=>$result->importId, 'product_id' => $productId, 'qty' => $qt,'expd' =>$result->expd]);
                               $j = $productId;
                               $a=0;

                           }elseif($a<$quantitiesRequest){

                               $qtyUpToImport = $qt- $result->qty;
                               $qt=$qtyUpToImport;
                               DB::table('import_product')->where('id', $result->id)->update(array('qty'=>0));
                               DB::table('import_stockoutre')->insert(['stockoutre_id'=>$stockoutreid,'import_id'=>$result->importId, 'product_id' => $productId, 'qty' => $result->qty,'expd' =>$result->expd]);
                           }

                       }

                   }//end main If
                }

            }
            $requestproUpdate = Requestpro::find($request->input('requestNumber'));
            $requestproUpdate->is_export= 1;
            $requestproUpdate->save();
        }

        return redirect('show/requested/product');

    }
    public function showResquestedProduct(){
        $stockoutre = Stockoutre::all();
        return view('admin.stock_out.showRequested',compact('stockoutre'));
    }

    public function showResquestedExport($stockoutre_id){
        $stockoutre = Stockoutre::find($stockoutre_id);
        return view('admin.stock_out.requestDetail',compact('stockoutre'));
    }

    public function createReturnRequest()
    {
        $stockoutres = Stockoutre::where('status','=',null)->get();
        $user = User::all();
        return view('admin.requestpro.createreturnrequest',compact('stockoutres','user'));
    }
    public function viewRequest($id)
    {
        if($id!=0){
            $stockoutreID = Stockoutre::where('requestpro_id','=',$id)->value('id');
            $stockoutreFilter = Stockoutre::findOrFail($stockoutreID);
            $data = $stockoutreFilter->imports;
            $details = Requestpro::findOrFail($id);
        }else{
            $data=false;
        }
        return view('admin.requestpro.viewinvoice',compact('details','data','stockoutreID'));
    }
     public function returnSomeRequestProduct($stId, $Qty, $qty,$proId,$impId,$returnBy,$Inv){//save return one by one ( return product )
          $stockoutId = Returnreqpro::where('stockoutre_id','=',$stId)->value('stockoutre_id');
          $qtyorder = $Qty-$qty;
        if(!$stockoutId){

            $returnreqpro = new Returnreqpro();
            $returnreqpro->stockoutre_id = $stId;
            $returnreqpro->returnBy=$returnBy;
            $returnreqpro->user_id = Auth::user()->id;
            $returnreqpro->save();
            $returnreqpro->products()->attach($proId,['qtyreturn'=>$qty,'qtyorder'=>$qtyorder]);
        }else{
            $Id = Returnreqpro::where('stockoutre_id','=',$stId)->value('id');
            $saveData = Returnreqpro::findOrFail($Id);
            $saveData->products()->attach($proId,['qtyreturn'=>$qty,'qtyorder'=>$qtyorder]);
        }

        $Import_Bqty =0;
        $product_Qty =Product::findOrFail($proId)->value('qty');
        $Import_qty=DB::table('import_product')->where([['importId','=',$impId],['productId','=',$proId],])->select('qty')->get();
        foreach ($Import_qty as $q){
            $Import_Bqty=$q->qty;
        }
        $Qty_update_import = $Import_Bqty + $qty;
        $Qty_update_product= $product_Qty + $qty;

        DB::table('import_product')->where([['importId','=',$impId],['productId','=',$proId],])->update(['qty'=>$Qty_update_import]);//Update QTY in table import_product
        DB::table('products')->where('id',$proId)->update(['qty'=>$Qty_update_product]);//Update QTY in table products
        DB::table('import_stockoutre')->where([['stockoutre_id','=',$stId],['import_id','=',$impId],['product_id','=',$proId],])->update(['status'=>1]);
        return  $this->viewRequest($Inv);

    }
    public  function SaveReturnAll($id,$userId){//save data that they are return all product
        $stockoutreId = Returnreqpro::where('stockoutre_id','=',$id)->value('stockoutre_id');
        if($stockoutreId){
            return "<h5 style='color: red;'>You cannot return all products on this invoice cuz you had returned some products on this invoice</h5>";
        }else{
            $result= DB::table('import_stockoutre')->where([['stockoutre_id','=',$id],['status','=',null],])->get();
            if($result->count()>0){
                foreach ($result as $re){
                    $Import_qty=0;
                    $UpQty=0;
                    $product_qty=Product::where('id',$re->product_id)->value('qty');
                    $import_product = DB::table('import_product')->where([['importId','=',$re->import_id],['productId','=',$re->product_id],['expd','=',$re->expd]])->select('qty')->get();
                    foreach ($import_product as $q){
                        $Import_qty=$q->qty;
                        $UpQty =$Import_qty+$re->qty;
                    }
                    DB::table('import_product')->where([['importId','=',$re->import_id],['productId','=',$re->product_id],['expd','=',$re->expd],])->update(['qty'=>$UpQty]);
                    $UQty = $product_qty+$re->qty;
                    DB::table('products')->where('id',$re->product_id)->update(['qty'=>$UQty]);
                }
                DB::table('import_stockoutre')->where('stockoutre_id',$id)->update(['status'=>1]);
                $requestpro = Stockoutre::where('id',$id)->value('requestpro_id');
                $req = Requestpro::findOrFail($requestpro);
                $req->returntype = "ra";
                $req->save();
                $returnreq = new Returnreqpro();
                $returnreq->stockoutre_id=$id;
                $returnreq->returnBy=$userId;
                $returnreq->user_id= Auth::user()->id;
                $returnreq->status="a";
                $returnreq->save();
                $stockoutre = Stockoutre::find($id);
                $stockoutre->status = 1;
                $stockoutre->save();
                return "<h5 style='color: darkblue;'>Returned successfully...</h5>";
            }
        }
    }
    public function viewReturnRequest()
    {
        $returnreqpro = Returnreqpro::all();
        return view('admin.requestpro.indexreturn',compact('returnreqpro'));
    }
    public function viewProductReturn($returnProId,$status,$stockoutId){

        if(strtolower($status)=="s"){
            $returnpro = DB::table('product_returnreqpro')->where('returnreqpro_id',$returnProId)->selectRaw('product_id, sum(qtyreturn) as QR, sum(qtyorder) as QO, sum(qtyreturn)+sum(qtyorder) as TQ')->groupBy('product_id')->get();
            return view('admin.requestpro.viewproductreturn',compact('returnpro'));
        }elseif(strtolower($status)=="a"){

            $id = Stockoutre::findOrFail($stockoutId)->value('requestpro_id');
            $requestpros =Requestpro::findOrFail($id);
            $requestData = $requestpros->products;
            return view('admin.requestpro.viewproductreturnall',compact('requestData'));

        }

    }
}
