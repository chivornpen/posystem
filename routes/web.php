<?php
use App\Customer;
use App\Usage;
use App\Product;
use App\TpmPurchaseOrder;
use App\Purchaseorder;
use App\Pricelist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\TpmEditPurchaseOrder;
use App\User;
use App\Tmppurchaseordercussd;
use App\Tmpeditpurchaseordercussd;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix' => 'admin','middleware'=>'auth'], function () {
   	Route::get('/','DashbordController@index');
	Route::resource('dashbords','DashbordController');
	Route::resource('positions','PositionController');
	Route::resource('brands','BrandController');
	Route::resource('zones','ZoneController');
	Route::resource('users','UserController');
	Route::get('/resets/{id}','UserController@reset');
	Route::get('/updatelogs1/{id}','UserController@updateLog1');
	Route::get('/updatelogs0/{id}','UserController@updateLog0');
	Route::patch('/updates/{id}','UserController@updatepw');
	Route::resource('customers','customerController');
	Route::resource('categories','CategoryController');
	Route::resource('products','ProductController');
	Route::resource('channels','ChannelController');
	Route::resource('provinces','ProvinceController');
	Route::resource('districts','DistrictController');
	Route::resource('communes','CommuneController');
	Route::resource('villages','VillageController');
	Route::resource('invoices','InvoiceController');
	Route::resource('usages','UsageController');
	Route::resource('purchaseOrders','PurchaseOrderController');
	Route::get('/cancel','PurchaseOrderController@cancel');
	Route::resource('saleSD','SaleSDController');
	Route::get('/cussdcancel','SaleSDController@cussdcancel');
	Route::resource('suppliers','SupplierController');
	Route::resource('pricelists','PriceListController');
	Route::resource('purchaseOrdersSD','PurchaseOrderSDController');
	Route::get('/sdcancel','PurchaseOrderSDController@sdcancel');
	Route::resource('invoicePO','InvoicePOController');
	Route::get('/income/payment','InvoicePOController@createPayment');
	Route::post('submit','InvoicePOController@submit');
	Route::get('/view/income/payment','InvoicePOController@viewincomepayment');
	Route::get('/income/statement','InvoicePOController@incomeStatement');
	Route::post('/income/report/filter/date','InvoicePOController@incomeReportFilter');
	Route::get('/showpaid/{id}','InvoicePOController@showInvoicePaid');
	Route::resource('summaryInv','CraditPOController');
	Route::resource('stocks','StockController');
	Route::resource('setValues','SetValueController');
	Route::resource('verifys','TmpEditPurchaseorderController');
	Route::resource('stockoutsd','StockoutSdController');
	Route::resource('exchangesd','ExchangesdController');
	Route::resource('productreturnsd','ProductReturnSDController');
	Route::resource('stockreport','StockReportController');
	Route::resource('sdstockreport','SDStockReportController');
	Route::resource('requestpro','RequestproController');
	Route::get('/set/variable','SetvariableController@create');
	Route::post('set/variable/store','SetvariableController@save');
	Route::get('/discard','SetvariableController@discard');
	Route::get('/discard','RequestproController@discard');
	Route::get('/createverify','RequestproController@createverify');
	Route::get('/viewreturnrequest','RequestproController@viewReturnRequest');
	Route::get('/createreturnrequest','RequestproController@createReturnRequest');
	Route::post('/confirm','RequestproController@confirm');
	Route::get('/sdreportstockout','SDStockReportController@sdreportstockout');
	Route::get('/sdreportstockreturn','SDStockReportController@sdreportstockreturn');
	Route::get('/sdreportstockexchange','SDStockReportController@sdreportstockexchange');
	Route::get('/reportStockOut','StockReportController@reportStockOut');
	Route::get('/reportStockExchange','StockReportController@reportStockExchange');
	Route::get('/reportStockReturn','StockReportController@reportStockReturn');
	Route::get('/createPoExchange','ExchangesdController@createPoExchange');
	Route::get('/createInvoiceReturnzSd','ProductReturnSDController@createInvoiceReturnzSd');
	Route::get('/inv','InvoiceController@inv');
	Route::get('/invoice','InvoiceController@invoice');
	Route::resource('summaryInvs','CraditPOController');
	Route::get('/details/{id}','CraditPOController@detail');
	Route::post('/updatePro','PurchaseOrderController@updatePro');
	Route::get('/showEdit/{poid}','PurchaseOrderController@showEdit');
	Route::post('/deletePro','PurchaseOrderController@deletePro');
	Route::post('/deleteProcussd','SaleSDController@deleteProcussd');
	Route::get('/showEditcussd/{poid}','SaleSDController@showEditcussd');
	Route::post('/updateProCussd','SaleSDController@updateProCussd');
});
//endAdmin
Route::get('/saerchDateStockIn/{startDate}/{endDate}','StockReportController@saerchDateStockIn');
Route::get('/saerchDateStockOut/{startDate}/{endDate}','StockReportController@saerchDateStockOut');
Route::get('/saerchDateStockExchange/{startDate}/{endDate}','StockReportController@saerchDateStockExchange');
Route::get('/saerchDateStockReturnpro/{startDate}/{endDate}','StockReportController@saerchDateStockReturnpro');
//--------------search report sd -----------
Route::get('/searchIn/{brand_id}/{startDate}/{endDate}','SDStockReportController@searchIn');
Route::get('/searchOut/{brand_id}/{startDate}/{endDate}','SDStockReportController@searchOut');
Route::get('/searchBalance/{brand_id}','SDStockReportController@searchBalance');
Route::get('/searchReturn/{brand_id}/{startDate}/{endDate}','SDStockReportController@searchReturn');
Route::get('/searchExchange/{brand_id}/{startDate}/{endDate}','SDStockReportController@searchExchange');
//------------------end search---------------
//----------------------select customer------------------
Route::get('/getCustomer/{id}',function($id){
	 	$customers = Customer::where('id','=', $id)->first();
	 	$channels = $customers->channel()->select('id','description')->first();
        return response()->json([$customers,$channels]);
});
//----------------------
Route::get('/addRequestpro/{proid}/{qty}','RequestproController@addRequestpro');
Route::get('/showProduct','RequestproController@showProduct');
Route::get('/removeRequestpro/{id}','RequestproController@removeRequestpro');
Route::get('/showDetail/{id}','RequestproController@show');
Route::get('/getRequestProduct/{id}','RequestproController@getRequestProduct');
Route::get('/viewrequest/{id}','RequestproController@viewRequest');
Route::get('/returnsomereqpro/{stId}/{Qty}/{qty}/{proID}/{impId}/{returnBy}/{Inv}','RequestproController@returnSomeRequestProduct');//save return one by one
Route::get('/returnAll/{id}/{userId}','RequestproController@SaveReturnAll');
Route::get('/viewproductreturn/{returnId}/{status}/{stockoutId}','RequestproController@viewProductReturn'); //view product return
//------------------purchaseorder customer---------------
//-------------------------------
Route::get('/addOrderCus/{proid}/{qty}/{price}/{amount}','PurchaseOrderController@addOrderCus');
Route::get('/showProductCus','PurchaseOrderController@showProductCus');
Route::get('/getPopup','PurchaseOrderController@popupCus');
Route::get('/removeOrderCus/{id}',function($id){
	 	$tpmPurchaseOrders = TpmPurchaseOrder::where('id','=', $id)->first();
	 	$tpmPurchaseOrders->delete();
        return response()->json($id);
});
Route::get('/getTotalCus',function(){
	$tmp = App\TpmPurchaseOrder::all();
	$total= $tmp->where('user_id','=',Auth::user()->id)->sum('amount');
	return response()->json($total);
});
//-----------------------purchaseorder sd ---------------
Route::get('/addOrderSD/{proid}/{qty}/{price}/{amount}','PurchaseOrderSDController@addOrderSD');
Route::get('/showProductSD','PurchaseOrderSDController@showProductSD');
Route::get('/removeOrderSD/{id}',function($id){
	 	$tpmPurchaseOrders = TpmPurchaseOrder::where('id','=', $id)->first();
	 	$tpmPurchaseOrders->delete();
        return response()->json($id);
});
Route::get('/getTotalSD',function(){
	$tmp = App\TpmPurchaseOrder::all();
	$total= $tmp->where('user_id','=',Auth::user()->id)->sum('amount');
	return response()->json($total);
});
//------------------purchaseorder customer of sd----------------------
Route::get('/addOrderSDSale/{proid}/{qty}/{price}/{amount}','SaleSDController@addOrderSDSale');
Route::get('/showProductCussd','SaleSDController@showProductCussd');
Route::get('/getPopupCusSD','SaleSDController@getPopupCusSD');
Route::get('/getTotalCussd',function(){
	$tmp = App\Tmppurchaseordercussd::all();
	$total= $tmp->where('user_id','=',Auth::user()->id)->sum('amount');
	return response()->json($total);
});
Route::get('/removeOrderCussd/{id}',function($id){
	 	$tpmPurchaseOrders = Tmppurchaseordercussd::where('id','=', $id)->first();
	 	$tpmPurchaseOrders->delete();
        return response()->json($id);
});
//---------------------get product in stock of sd----------------------------
Route::get('/getProductSubStock/{id}',function($id){
		$brandid = User::where('id','=',Auth::user()->id)->value('brand_id');
		$qtySub = DB::select("SELECT qty FROM brand_product WHERE brand_id={$brandid} AND product_id=$id");
		foreach ($qtySub as $qtys) {
			$qtySubStock = $qtys->qty;
		}
		$oldQty = Tmppurchaseordercussd::where('product_id','=',$id)->where('user_id','=',Auth::user()->id)->value('qty');
		$product_code = Product::where('id','=', $id)->value('product_code');
	 	return response()->json(['pro_code'=>$product_code,'qtySubStock'=>$qtySubStock,'tmp_pro_qty'=>$oldQty]);
	});
Route::get('/getProductSubStockEdit/{id}',function($id){
		$brandid = User::where('id','=',Auth::user()->id)->value('brand_id');
		$qtySub = DB::select("SELECT qty FROM brand_product WHERE brand_id={$brandid} AND product_id=$id");
		foreach ($qtySub as $qtys) {
			$qtySubStock = $qtys->qty;
		}
		$oldQty = Tmpeditpurchaseordercussd::where('product_id','=',$id)->where('user_id','=',Auth::user()->id)->where('recordStatus','!=','r')->value('qty');
		$product_code = Product::where('id','=', $id)->value('product_code');
	 	return response()->json(['pro_code'=>$product_code,'qtySubStock'=>$qtySubStock,'tmp_pro_qty'=>$oldQty]);
	});

Route::get('/getIdPoSd/{id}','StockoutsdController@getIdPoSd');//Route for event onchange in combobox invoice number
Route::get('/viewDetailStockoutSd/{id}','StockoutsdController@viewDetailStockoutSd');//View detail stock out of sd
//-----------------product exchange of sd ------------------
Route::get('/showProductExchangeSd/{id}','ExchangesdController@showProductExchangeSd');
Route::get('/saveExchangesd/{importI}/{productI}/{qty}/{expd}/{stockout}','ExchangesdController@saveExchangesd');//save data when exchange
Route::get('/viewDetailExchangesd/{id}','ExchangesdController@viewDetailExchangesd');
Route::get('/listInvoiceExchange/{id}','ExchangesdController@listInvoiceExchange');
Route::get('/createNewInvoice/{id}','ExchangesdController@createNewInvoice');
//-------------------------end----------------------------
//------------------------product return of sd--------------
Route::get('/showInvoiceReturn/{id}','ProductReturnSDController@showInvoiceReturn');//view invoice onchange comboBox
Route::get('/returnProductOneByOne/{stId}/{Qty}/{qty}/{proID}/{impId}/{returnBy}/{Inv}','ProductReturnSDController@returnProductOneByOne');//save return one by one
Route::get('/ReturnAllProduct/{id}/{userId}','ProductReturnSDController@ReturnAllProduct');//save record return all
Route::get('/createInvoiceReturnzSd','ProductReturnSDController@createInvoiceReturnzSd');
Route::get('/viewProductReturn/{returnId}/{status}/{stockoutId}','ProductReturnSDController@viewProductReturn'); //view product return
//show conten invoice return when chose in drop down
Route::get('/showContentInvReturn/{returnId}/{status}','ProductReturnSDController@showContentInvReturn');
//create Invoice Product Return
Route::get('/productReturnCreateInv/{returnId}/{status}','ProductReturnSDController@productReturnCreateInv');



//-----------------------end--------------------
//------------------------get product to select combobox-------------------------
Route::get('/getProduct/{id}',function($id){
		$oldQty = TpmPurchaseOrder::where('product_id','=',$id)->where('user_id','=',Auth::user()->id)->value('qty');
		$product_code = Product::where('id','=', $id)->value('product_code');
		$qty_product = Product::where('id','=', $id)->value('qty');
	 	$products = Product::findOrFail($id);
	 	$now = Carbon::now()->toDateString();
	 	foreach ($products->pricelists as $product) {
	 		$pricelist_id = $product->id;
	 		// $sql = "SELECT sellingprice FROM pricelists WHERE id={$pricelist_id} AND now()>=startdate AND now()<=enddate";
		 	$price = DB::table('pricelists')->where([['id','=',$pricelist_id],['startdate','<=',$now],['enddate','>=',$now],])->value('sellingprice');
		 	
	 	}
	 	return response()->json(['pro_code'=>$product_code,'qty_product'=>$qty_product,'tmp_pro_qty'=>$oldQty,'price'=>$price]);
	});
//------------------------get product in edit purchaseorder----------------------
Route::get('/getProductVer/{id}',function($id){
		$oldQty = TpmEditPurchaseOrder::where('product_id','=',$id)->where('recordStatus','!=','r')->where('user_id','=',Auth::user()->id)->value('qty');
		$product_code = Product::where('id','=', $id)->value('product_code');
		$qty_product = Product::where('id','=', $id)->value('qty');
	 	$products = Product::findOrFail($id);
	 	$now = Carbon::now()->toDateString();
	 	foreach ($products->pricelists as $product) {
	 		$pricelist_id = $product->id;
	 		$price = DB::table('pricelists')->where([['id','=',$pricelist_id],['startdate','<=',$now],['enddate','>=',$now],])->value('sellingprice');
	 	}
	 	return response()->json(['pro_code'=>$product_code,'qty_product'=>$qty_product,'tmp_pro_qty'=>$oldQty,'price'=>$price]);
	});
//--------------------------------Address select combobox---------------------
Route::get('/getProvince/{id}',function($id){
	 	$district = DB::table('districts')->select('id','name')->where('province_id','=', $id)->get();
        return response()->json($district);
});
Route::get('/getDistrict/{id}',function($id){
	 	$commune = DB::table('communes')->select('id','name')->where('district_id','=', $id)->get();
        return response()->json($commune);
});
Route::get('/getCommune/{id}',function($id){
	 	$village = DB::table('villages')->select('id','name')->where('commune_id','=', $id)->get();
        return response()->json($village);
});
//---------------------------get purchaseorder infomation---------------------------
Route::get('/getPOInfo/{id}',function($id){
	 	$po = Purchaseorder::where('id','=', $id)->get();
        return response()->json($po);
});
Route::get('/getPO/{id}',function($id){
	$po = Purchaseorder::with('customer')->where('customer_id','=',$id)->where('isPayment','=',0)->get();
	return response()->json($po);
});
Route::get('/getProuctVerTmpPo/{id}','TmpEditPurchaseorderController@getProuctVerTmpPo');
Route::get('/updateGenerate/{id}','InvoicePOController@updateGenerate');
Route::get('/getPopupEditPO/{id}','InvoicePOController@getPopupEditPO');
Route::get('/getPopupEditCradit/{id}','InvoicePOController@getPopupEditCradit');
Route::get('/getPopupEditInvoice/{id}','StockController@getPopupEditInvoice');
Route::get('/getPopupEditProduct/{poid}/{proid}','PurchaseOrderController@getPopupEditProduct');
Route::get('/getPopupEditProductEditCussd/{poid}/{proid}','SaleSDController@getPopupEditProductEditCussd');

//stock in _route
Route::get('/admin/stock', 'stock_in_controller@create');
Route::resource('/stock','stock_in_controller');
Route::get('/admin/stock/create/{id}/{qty}/{mfd}/{expd}','stock_in_controller@tmpInsert')->name('tmpInsert');
Route::get('/admin/stock/create/{id}','stock_in_controller@delete')->name('delete');//Remove
Route::get('/admin/stock/viewRecord','stock_in_controller@viewRecord')->name('viewRecord');
Route::get('/admin/stock/discard','stock_in_controller@discard')->name('discard');
Route::get('/admin/stock/index','stock_in_controller@index')->name('index');
Route::get('/admin/stock/{id}', 'stock_in_controller@show');
Route::get('/admin/stock/current/{id}', 'stock_in_controller@showCurrent')->name('showCurrent');
Route::get('/admin/stock/views-all', 'stock_in_controller@viewsall');

//stock_out_route
Route::resource('/stockout', 'StockoutController');
Route::get('/stockout/change/{id}','StockoutController@InvNChange')->name('InvNChange');//Route for event onchange in combobox invoice number

//SD stock_in route
Route::resource('/sdstock','SdStockController');
Route::get('/admin/sdstock/current/{id}','SdStockController@ShowCurrentRecordSdStock');//show current record import
Route::get('/admin/sdstock/histories/{id}','SdStockController@ShowHistoryRecordSdStock');//show histories record import
Route::get('/sdstock/adminView/{id}','SdStockController@show');//Show record of each brand by ID and View it by Admin

//Product Exchange Route
Route::resource('/exchange','ProductExchange');
Route::get('/exchange/showRecord/{id}','ProductExchange@showRecord');
Route::get('/exchange/save/{importI}/{productI}/{qty}/{expd}/{stockout}','ProductExchange@saveRecord');//save data when exchange
Route::get('/exchange/viewDetail/{id}','ProductExchange@detail');


//create Exchange invoice
Route::get('/invoicePO/VUXchangeInvoice/{id}','InvoicePOController@view');
Route::get('/invoicePo/Xchange/{id}','InvoicePOController@createXchangeInvoice');

//ProductReturn
Route::resource('/return','ProductReturn');
Route::get('/return/VUinvoice/{id}','ProductReturn@show');//view invoice onchange comboBox

Route::get('/return/ReturnAll/{id}/{userId}','ProductReturn@SaveReturnAll');//save record return all
Route::get('/return/save/one/{stId}/{Qty}/{qty}/{proID}/{impId}/{returnBy}/{Inv}','ProductReturn@save');//save return one by one
Route::get('/return/viewProductReturn/{returnId}/{status}/{stockoutId}','ProductReturn@viewProductReturn'); //view product return

//show invoice return in drop down selector
Route::get('/invoicePo/ProductReturn/view','InvoicePOController@ProductReturn');//show invoice have to return in comboBox

//show conten invoice return when chose in drop down
Route::get('/invoicePo/showcontent/view/{returnId}/{status}','InvoicePOController@showContentInvoiceReturn');

//create Invoice Product Return
Route::get('/invoicePo/ProductReturn/invoice/create/{returnId}/{status}','InvoicePOController@ProductReturnInvoice');

//Report Route
Route::resource('/report','reportController');
Route::get('/report/search/customer/{data}/{brand}','reportController@show');//search customer list

Route::get('/report/sale/view','reportController@SaleReport');//Sale Report

Route::get('/report/sale/search/{saleName}/{startDate}/{endDate}/{brand}','reportController@SaleReportSearch');//search sale report


//Payment Report
Route::get('/report/payment/views/report','reportController@paymentReport');//payment Report
//payment report search
Route::get('/report/payment/search/report/{custName}/{startDate}/{endDate}','reportController@paymentReportSearch');//payment Report search


//customer credit Report
Route::get('/report/customerCredit/views/report','reportController@customerCredit');//payment Report
//cutomer credit report search
Route::get('/report/customerCredit/search/report/{cusName}/{startDate}/{endDate}','reportController@customerCreditSearch');//payment Report search

//expired_product
Route::get('/report/expired/prouduct','reportController@expiredProduct');

//SD Stock search by Brand
Route::get('/report/expired/sdstock/{brandName}','reportController@sdStockSearchByBrand');

//SD Customer Expired search
Route::get('/report/expired/customerSd/{brandName}','reportController@sdCustomerSearchByBrand');

//Export Request product
Route::get('/show/requestPro/','RequestproController@showRequestToExport');

Route::post('/export/request/product','RequestproController@exportRequestPro');

Route::get('/export/request/product/detail/{request_id}','RequestproController@showRequestDetail');

Route::get('/show/requested/product','RequestproController@showResquestedProduct');

Route::get('/show/requested/export/detail/{stockoutreId}','RequestproController@showResquestedExport');

///Accounting Route
Route::get('/account/create/booking','Accounting@index');

Route::post('/account/create/booking/create','Accounting@create');


//Account Type
Route::get('/account/create/acc/type','Accounting@CreateAccType');
Route::post('/account/create/acc/type/stored','Accounting@storedAccType');

//Chart Account
Route::get('/account/create/acc/chart','Accounting@CreateAccChart');
Route::post('/account/create/acc/chart/stored','Accounting@storedAccChart');
Route::get('/account/get/sign/{id}','Accounting@getSign');


Route::get('/account/delete/{id}/{modelName}','Accounting@delete');
Route::get('/account/edit/{id}/{modelName}','Accounting@edit');

Route::patch('/account/accType/update/{id}','Accounting@update');

Route::get('/account/selectChartAcc/{id}','Accounting@selectChartAcc');
Route::get('/account/submitBook','Accounting@clearBook');
Route::get('/account/dTransition/{id}','Accounting@deleteTransition');



//Account Report

Route::get('/account/report/balance/sheet','Accounting@BalanceSheetReport');
Route::post('/account/report/filter/date','Accounting@BanlanceSearch');

Route::get('/account/report/trial/balance','Accounting@trialBalance');
Route::post('/account/report/trial/balance/filter','Accounting@trialBalanceFilter');



























Route::get('/welcome1',function (){
    return "hello";
});

// Route::get('/getEndNoCus/{customer_id}',function($customer_id){
// 	 	$endNo = Usage::select('endNo')->where('customer_id','=', $customer_id)->orderBy('created_at', 'desc')->value('endNo');
// 	 	if($endNo==''){
// 	 		return response()->json(0);
// 	 	}else{
// 	 		return response()->json($endNo);
// 	 	}
        
// });
