@extends('layouts.admin')
@section('content')
        <div class="container-fluid">
            <br><br>
        @if(strtolower(\Illuminate\Support\Facades\Auth::user()->position->name)!="sd")
            <div class="panel panel-default">
                <div class="panel-heading">Expired Main Stock</div>
                <div class="panel-body">
                    <div style="overflow: auto;">
                        <div id="expired">
                            @if(count($count))
                                <p style="display: none; font-size: 1px;">ក</p>
                                <img src="{{asset('/images/Logo.jpg')}}" style="height: 15px; width: 110px; margin: 10px 0 10px 0"><br>
                                <p style="font-family: 'Times New Roman',Serif;color: #cf3d54; font-size:12px;"><b>MAIN STOCK EXPIRED PRODUCTS  </b></p>
                                <table border="1px" cellpadding="5px" id="expired" style=" width: auto;border-collapse: collapse; border:1px solid #7a7a7a;">
                                    <thead>
                                    <tr>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center; padding:2px 8px;">No</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Imports Number</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Imports Date</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Invoices Number</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Invoices Date</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px;">Products Code</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Quantities</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Manufacture Date</td>
                                        <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px; padding:2px 8px;">Expired Date</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $n =1; ?>
                                    @foreach($import as $im)
                                        @foreach($im->products()->whereBetween('expd',[\Carbon\Carbon::now()->toDateString('Y-m-d'),\Carbon\Carbon::now()->addYear(1)->toDateString('Y-m-d')])->get() as $p)
                                            @if($p->qty > 0)
                                                <tr>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{$n++}}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman'; font-size: 12px;padding:2px 8px;">{!! "IMP-".$im->id !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman'; font-size: 12px;padding:2px 8px;">{!! \Carbon\Carbon::parse($im->impDate)->format('d-M-Y') !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman'; font-size: 12px;padding:2px 8px;">{!! $im->invoiceNumber !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman'; font-size: 12px;padding:2px 8px;">{!! \Carbon\Carbon::parse($im->invoiceDate)->format('d-M-Y') !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman'; font-size: 12px;padding:2px 8px;">{!! $p->product_code !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman'; font-size: 12px;padding:2px 8px;">{!! $p->pivot->qty !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman'; font-size: 12px;padding:2px 8px;">{!! \Carbon\Carbon::parse($p->pivot->mfd)->format('d-M-Y') !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman'; font-size: 12px;padding:2px 8px;">{!! \Carbon\Carbon::parse($p->pivot->expd)->format('d-M-Y') !!}</td>

                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <h5>No found results</h5>
                            @endif
                        </div>
                        <br>
                    </div>
                </div>
                <div class="panel-footer">
                    <a style="text-decoration:none;" href="#" class="btn-primary btn-sm" title="Print" id="btnPrintReport"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                    <a style="text-decoration:none;" href="#" class="btn-success btn-sm" title="Excel" id="btnExportExcel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Expired Customer</div>
                <div class="panel-body"​>
                    <div style="overflow: auto;">
                        <div id="expiredCustomer">
                            @if(count($counti))
                                <p style="display: none; font-size: 1px;">ក</p>
                                <img src="{{asset('/images/Logo.jpg')}}" style="height: 15px; width: 110px; margin: 10px 0 10px 0"><br>
                                <p style="font-family: 'Times New Roman',Serif;color: #cf3d54; font-size:12px;"><b>CUSTOMER EXPIRED PRODUCTS​ </b></p>
                                <table border="1px" cellpadding="5px" id="expired" style=" width: auto;border-collapse: collapse; border:1px solid #7a7a7a;">

                                    <thead>
                                    <tr>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center; padding:2px 8px;">No</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Invoices Number</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Stock Out Date</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Customer Number</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Customer Name</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Branchs</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px;">Products Code</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Quantities</td>
                                        <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px; padding:2px 8px;">Expired Date</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $n =1; ?>
                                    @foreach($stockout as $stock)
                                        @foreach($stock->imports()->whereBetween('expd',[\Carbon\Carbon::now()->toDateString(),\Carbon\Carbon::now()->addYear(1)->toDateString()])->where('status',null)->get() as $cu)
                                            <tr>
                                                @if($cu->pivot->qty > 0)
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $n++ !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! "CAM-IN-".sprintf('%06d',$stock->purchaseorder_id) !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!!\Carbon\Carbon::parse($stock->stockoutDate)->format('d-M-Y') !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! \App\Purchaseorder::where('id',$stock->purchaseorder_id)->value('customer_id') ? "CAM-CUS-".sprintf('%06d',\App\Purchaseorder::where('id',$stock->purchaseorder_id)->value('customer_id')) : "CAM-CUS-".sprintf('%06d',\App\Customer::where('contactNo',\App\User::where('id',\App\Purchaseorder::where('id',$stock->purchaseorder_id)->value('user_id'))->value('contactNum'))->value('id')) !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! \App\Purchaseorder::where('id',$stock->purchaseorder_id)->value('customer_id') ? \App\Customer::where('id',\App\Purchaseorder::where('id',$stock->purchaseorder_id)->value('customer_id'))->value('name') : \App\User::where('id', \App\Purchaseorder::where('id',$stock->purchaseorder_id)->value('user_id'))->value('nameDisplay') !!}</td>
                                                    <td style=" font-family: 'Khmer OS System',Serif;font-size: 12px;padding:2px 8px;">{!! \App\Brand::where('id',\App\User::where('id',\App\Purchaseorder::where('id', $stock->purchaseorder_id)->value('user_id'))->value('brand_id'))->value('brandName') !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! \App\Product::where('id',$cu->pivot->product_id)->value('product_code') !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $cu->pivot->qty !!}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! \Carbon\Carbon::parse($cu->pivot->expd)->format('d-M-Y') !!}</td>
                                                    {{--\App\Product::where('id',$cu->product_id)->value('product_code')--}}
                                                    {{--\App\User::where('id',\App\Purchaseorder::where('id',\App\Stockout::where('id',$cu->stockout_id)->value('purchaseorder_id'))->value('user_id'))->value('contactNum')--}}
                                                @endif

                                            </tr>
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <h5>No found results</h5>
                            @endif
                        </div>
                        <br>
                    </div>
                </div>
                <div class="panel-footer">
                    <a style="text-decoration:none;" href="#" class="btn-primary btn-sm" title="Print" id="btnPrintReportExpiredCustomer"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                    <a style="text-decoration:none;" href="#" class="btn-success btn-sm" title="Excel" id="btnPrintReportExpiredCustomerExcel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
                </div>
            </div>

        @endif

            {{--SD STOCK EXPIRED--}}
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if(strtolower(\Illuminate\Support\Facades\Auth::user()->position->name)!="sd")
                       <div class="row">
                           <div class="col-lg-4">
                               <select name="brandName" id="brandName" class="form-control" onchange="StockSdByBrand()">
                                   <option value="0">Branch Name</option>
                                   @foreach($brand as $b)
                                        <option value="{{$b->id}}">{!! $b->brandName !!}</option>
                                   @endforeach
                               </select>
                           </div>
                       </div>
                    @else
                        STOCK EXPIRED PRODUCTS
                    @endif

                </div>
                <div class="panel-body">
                    <div style="overflow: auto;">
                        <div id="Sdexpired">
                            @if(count($sdCount))
                                <p style="display: none; font-size: 1px;">ក</p>
                                <img src="{{asset('/images/Logo.jpg')}}" style="height: 15px; width: 110px; margin: 10px 0 10px 0"><br>
                                <p style="font-family: 'Times New Roman',Serif;color: #cf3d54; font-size:12px;"><b>STOCK EXPIRED PRODUCTS</b></p>
                                <table border="1px" cellpadding="5px" id="expired" style=" width: auto;border-collapse: collapse; border:1px solid #7a7a7a;">
                                    <thead>
                                    <tr>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center; padding:2px 8px;">No</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Invoices Number</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Import Date</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Supplier</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px;">Products Code</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Quantities</td>
                                        <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px; padding:2px 8px;">Expired Date</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $n =1; ?>
                                    @foreach($subimport as $sub)
                                        @foreach($sub->products()->whereBetween('expd',[\Carbon\Carbon::now()->toDateString('Y-M-d'),\Carbon\Carbon::now()->addYear(1)->toDateString('Y-M-d')])->get() as $subPro)
                                            <tr>
                                                @if($subPro->pivot->qty > 0)
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{$n++}}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{"CAM-IN-".sprintf('%06d',$sub->purchaseorder_id)}}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ \Carbon\Carbon::parse($sub->subimportDate)->format('d-M-Y') }}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ $sub->supplier->companyname }}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ $subPro->product_code}}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ $subPro->pivot->qty }}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ \Carbon\Carbon::parse($subPro->pivot->expd)->format('d-M-Y') }}</td>
                                                    {{--\App\Product::where('id',$cu->product_id)->value('product_code')--}}
                                                    {{--\App\User::where('id',\App\Purchaseorder::where('id',\App\Stockout::where('id',$cu->stockout_id)->value('purchaseorder_id'))->value('user_id'))->value('contactNum')--}}
                                                @endif
                                        @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <h5>No found results</h5>
                            @endif
                        </div>
                        <br>
                    </div>
                </div>
                <div class="panel-footer">
                    <a style="text-decoration:none;" href="#" class="btn-primary btn-sm" title="Print" id="btnPrintReportSD"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                    <a style="text-decoration:none;" href="#" class="btn-success btn-sm" title="Excel" id="btnExportExcelSD"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
                </div>
            </div>

{{--TO CUSTOMER SD --}}
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if(strtolower(\Illuminate\Support\Facades\Auth::user()->position->name)!="sd")
                        <div class="row">
                            <div class="col-lg-4">
                                <select name="SDBrand" id="SDBrand" class="form-control" onchange="SdCustomerexpired()">
                                    <option value="0">Branch Name</option>
                                    @foreach($brand as $b)
                                        <option value="{{$b->id}}">{!! $b->brandName !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @else
                        EXPIRED PRODUCTS TO CUSTOMER
                    @endif

                </div>
                <div class="panel-body">
                    <div style="overflow: auto;">
                        <div id="SdCustomerexpired">
                            @if(count($customerSdCount))
                                <p style="display: none; font-size: 1px;">ក</p>
                                <img src="{{asset('/images/Logo.jpg')}}" style="height: 15px; width: 110px; margin: 10px 0 10px 0"><br>
                                <p style="font-family: 'Times New Roman',Serif;color: #cf3d54; font-size:12px;"><b>CUSTOMER EXPIRED PRODUCTS</b></p>
                                <table border="1px" cellpadding="5px" id="expired" style=" width: auto;border-collapse: collapse; border:1px solid #7a7a7a;">
                                    <thead>
                                    <tr>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center; padding:2px 8px;">No</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Invoices Number</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Stock Date</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Customer Number</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Customer Name</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px;">Products Code</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Quantities</td>
                                        <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px; padding:2px 8px;">Expired Date</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $n =1; ?>
                                    @foreach($customerSd as $sdCus)
                                        @foreach($sdCus->subimports()->whereBetween('expd',[\Carbon\Carbon::now()->toDateString('Y-M-d'),\Carbon\Carbon::now()->addYear(1)->toDateString('Y-M-d')])->get() as $subPro)
                                            <tr>
                                                @if($subPro->pivot->qty > 0)
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{$n++}}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ "INV-".sprintf('%06d',$sdCus->purchaseordersd_id) }}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ \Carbon\Carbon::parse($sdCus->stockoutDate)->format('d-M-Y')  }}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ "CUS-".sprintf('%06d',\App\Purchaseordersd::where('id',$sdCus->purchaseordersd_id)->value('customer_id')) }}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ \App\Customer::where('id',\App\Purchaseordersd::where('id',$sdCus->purchaseordersd_id)->value('customer_id'))->value('name')}}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ \App\Product::where('id',$subPro->pivot->product_id)->value('product_code') }}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ $subPro->pivot->qty }}</td>
                                                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ \Carbon\Carbon::parse($subPro->pivot->expd)->format('d-M-Y') }}</td>

                                                    {{--\App\Product::where('id',$cu->product_id)->value('product_code')--}}
                                                    {{--\App\User::where('id',\App\Purchaseorder::where('id',\App\Stockout::where('id',$cu->stockout_id)->value('purchaseorder_id'))->value('user_id'))->value('contactNum')--}}
                                                @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <h5>No found results</h5>
                            @endif
                        </div>
                        <br>
                    </div>
                </div>
                <div class="panel-footer">
                    <a style="text-decoration:none;" href="#" class="btn-primary btn-sm" title="Print" id="btnPrintReportCSD"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                    <a style="text-decoration:none;" href="#" class="btn-success btn-sm" title="Excel" id="btnExportExcelCSD"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
                </div>
            </div>



        </div>
@stop

@section('script')
 <script src="{{asset('js/js.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/printThis.js')}}" type="text/javascript"></script>
<script type="text/javascript">

    $("#btnPrintReport").click(function () {
        $("#expired").printThis({
            loadCSS:""
        });
    });

    $("#btnPrintReportExpiredCustomer").click(function () {
        $("#expiredCustomer").printThis({
            loadCSS:""
        });
    });
    $("#btnPrintReportSD").click(function () {
        $("#Sdexpired").printThis({
            loadCSS:""
        });
    });
    $("#btnPrintReportCSD").click(function () {
        $("#SdCustomerexpired").printThis({
            loadCSS:""
        });
    });
    $("[id$=btnExportExcel]").click(function(e) {
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent( $('div[id$=expired]').html()));
        e.preventDefault();
    });
    $("[id$=btnPrintReportExpiredCustomerExcel]").click(function(e) {
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent( $('div[id$=expiredCustomer]').html()));
        e.preventDefault();
    });
    $("[id$=btnExportExcelSD]").click(function(e) {
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent( $('div[id$=Sdexpired]').html()));
        e.preventDefault();
    });
    $("[id$=btnExportExcelCSD]").click(function(e) {
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent( $('div[id$=SdCustomerexpired]').html()));
        e.preventDefault();
    });





    function StockSdByBrand() {
        var brandName = $('#brandName').val();
            $.ajax({
                type: 'get',
                url: "{{url('report/expired/sdstock')}}"+"/"+brandName,
                dataType: 'html',
                success:function (data) {
                    $('#Sdexpired').html(data);
                },
                error:function (error) {
                    console.log(error);
                }

            });
    }
    function SdCustomerexpired() {
       var SdCustomerexpired = $('#SDBrand').val();
       $.ajax({
           type: 'get',
           url: "{{url('report/expired/customerSd/')}}"+"/"+SdCustomerexpired,
           dataType: 'html',
           success:function (data) {
               $('#SdCustomerexpired').html(data);
           },
           error:function (error) {
               console.log(error);
           }

       });
    }
</script>
@stop