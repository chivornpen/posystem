@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">
                CUSTOMER CREDIT REPORTS
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class='col-md-3'>
                        <div class="form-group">
                            <select name="custname" id="custname" class="form-control" onchange="SaleReportSearch()">
                                <option value="0">Please select customer name</option>
                                @foreach($customer as $cus)
                                    <option value="{{$cus->id}}">{!! $cus->name !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class='col-md-3'>
                        <div class="form-group">
                            <div class='input-group date' id='StartDate' data-date="" data-date-format="dd-MM-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input type='text' class="form-control" placeholder="Start Date" id="SDate">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>

                    <div class='col-md-3'>
                        <div class="form-group">
                            <div class='input-group date' id='EndDate' data-date="" data-date-format="dd-MM-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input type='text' class="form-control" placeholder="End Date" id="EDate">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>

                    <div class='col-md-3'>
                        <div class="form-group">
                            <input type="button" value="Search" class="btn btn-primary" onclick="SaleReportSearch()">
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div style="overflow-x: scroll;">
                        <div id="CustomerCreditReport" >
                            @if($purchaseorder->count())
                                <img src="{{asset('/images/Logo.jpg')}}" style="height: 15px; width: 110px; margin: 10px 0 10px 0"><br>
                                <p style="font-family: 'Times New Roman',Serif;color: #cf3d54; font-size:12px;"><b> CUSTOMER CREDIT REPORTS</b></p>

                                <table border="1px" cellpadding="5px" id="customer" style=" width: 2500px; border-collapse: collapse; border:1px solid #7a7a7a;">
                                    <thead>
                                    <tr>
                                        <td colspan="11" style="border-top: 1px solid white; border-left: 1px solid white;"></td>
                                        <td colspan="{{$product->count()*2}}" style=" font-family:'Arial Black',Serif;font-size: 12px; text-align: center; padding: 3px;">Product Code</td>
                                    </tr>
                                    <tr>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center; padding:2px 8px;">No</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Customer Number</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Customer Name</td>
                                        <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px; padding:2px 8px;">Invoice Number</td>
                                        <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px; padding:2px 8px;">Due Date</td>
                                        <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px; padding:2px 8px;">Invoice Date</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Total Amount</td>
                                        <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Discount (%)</td>
                                        <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px;">COD (%)</td>
                                        <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px; padding:2px 8px;">Paid</td>
                                        <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px;">Credited</td>
                                        @if($product->count())
                                            @foreach($product as $pro)
                                                <td style="font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px; text-align: center; padding: 5px;" colspan="2">{{$pro->product_code}}</td>
                                            @endforeach
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1;?>
                                    @foreach($purchaseorder as $purchase)
                                        @if($purchase->status!="ra")
                                            <tr>
                                                <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{$i++}}</td>
                                                {{--<td style="text-align: center; font-family: 'Times New Roman'; font-size: 12px;padding:2px 8px;">{!! \Carbon\Carbon::parse($purchase->poDate)->format('d-M-Y') !!}</td>--}}
                                                {{--<td style="font-family: 'Khmer OS System',Serif;font-size: 12px;padding:2px 8px;">{!!strtoupper(\App\User::where('id',$purchase->user_id)->value('nameDisplay')) !!}</td>--}}
                                                <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $purchase->customer_id ? "CAM-CUS-".sprintf('%06d',$purchase->customer_id) : "CAM-CUS-".sprintf('%06d',\App\Customer::where('contactNo','=',\App\User::where('id',$purchase->user_id)->value('contactNum'))->value('id')) !!}</td>
                                                <td style=" font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! strtoupper($purchase->customer_id ? \App\Customer::where('id',$purchase->customer_id)->value('name') : \App\User::where('id',$purchase->user_id)->value('nameDisplay')) !!}</td>
                                                <td style=" font-family: 'Khmer OS System',Serif;font-size: 12px;padding:2px 8px; text-align: center;">{{ "CAM-IN-".sprintf('%06d', $purchase->id)}}</td>

                                                <td style=" font-family: 'Khmer OS System',Serif;font-size: 12px;padding:2px 8px; text-align: center;">{!! \Carbon\Carbon::parse($purchase->dueDate)->format('d-M-Y') !!}</td>
                                                <td style=" font-family: 'Khmer OS System',Serif;font-size: 12px;padding:2px 8px; text-align: center;">{!! \Carbon\Carbon::parse($purchase->invoiceDate)->format('d-M-Y') !!}</td>

                                                <td style="font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px; text-align: center;">{!! "$ ". number_format($purchase->totalAmount,2) !!}</td>
                                                <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $purchase->discount!!}</td>
                                                <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $purchase->cod !!}</td>
                                                <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! "$ ". number_format($purchase->paid,2) ? "$ ". number_format($purchase->paid,2) : "$ 0.00"  !!}</td>
                                                <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! "$ ". number_format($purchase->cradit,2) !!}</td>
                                                @foreach($product as $prod)
                                                    <?php
                                                    $proId = "";
                                                    $proQty="";
                                                    $unitPrice="";
                                                    $products = DB::table('purchaseorder_product')->where([['purchaseorder_id','=',$purchase->id],['product_id','=',$prod->id],])->get();
                                                    foreach($products as $row){
                                                        $proId = $row->product_id;
                                                        $proQty= $row->qty;
                                                        $unitPrice = $row->unitPrice;
                                                    }
                                                    ?>
                                                    @if($prod->id == $proId)
                                                        <td scope="col" style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px; color: red;">{!! "Q ". $proQty !!}</td>
                                                        <td scope="col" style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px; color: red;">{!!"P ". $unitPrice !!}</td>
                                                    @else
                                                        <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! "0" !!}</td>
                                                        <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! "0" !!}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                         @endif
                                    @endforeach
                                    </tbody>
                                </table>
                        </div>
                        <br>
                        <a style="text-decoration:none;" href="#" class="btn-primary btn-sm" title="Print" id="btnPrintReport"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                        <a style="text-decoration:none;" href="#" class="btn-success btn-sm" title="Excel" id="btnExportExcel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
                        <br><br>
                        @else
                            <h5>No found results</h5><br>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('js/js.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/printThis.js')}}" type="text/javascript"></script>

    <script type="text/javascript">

        function SaleReportSearch() {
            var custName = $('#custname').val();
            var startDate = $('#SDate').val();
            var endDate = $('#EDate').val();
            var error = "";

            if(startDate==""){
                startDate = 0;
            }
            if(endDate=="" || endDate<startDate){
                endDate = 0;
            }
            if(error==""){
                $.ajax({
                    type: 'get',
                    url: "{{url('report/customerCredit/search/report/')}}"+"/"+custName+"/"+startDate+"/"+endDate,
                    dataType: 'html',
                    success:function (data) {
                        $('#CustomerCreditReport').html(data);
                    },
                    error:function (error) {
                        console.log(error);
                    }
                });
            }
        }


        $(document).ready(function() {
            $("#custname").select2();
        });

        $("#btnPrintReport").click(function () {
            $("#CustomerCreditReport").printThis({
                loadCSS:""
            });
        });
        $("[id$=btnExportExcel]").click(function(e) {
            window.open('data:application/vnd.ms-excel,' + encodeURIComponent( $('div[id$=CustomerCreditReport]').html()));
            e.preventDefault();
        });

        $(function () {
            $('#StartDate').datetimepicker({
                language:  'en',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0
            });
            $('#EndDate').datetimepicker({
                language:  'en',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0
            });
//            $("#StartDateate").on("dp.change", function (e) {
//                $('#StartDateate').data("dateTimePicker").minDate(e.date);
//            });
//            $("#EndDate").on("dp.change", function (e) {
//                $('#EndDate').data("dateTimePicker").maxDate(e.date);
//            });
        });
    </script>
@endsection

{{--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>--}}
{{--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}

{{--<script type="text/javascript">--}}
{{--function pickdate() {--}}
{{--$('#date').datepicker();--}}
{{--}--}}
{{--</script>--}}