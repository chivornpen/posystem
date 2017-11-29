@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">
                BALANCE SHEET
                <a href="{{url('admin/dashbords')}}" style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-close fa-1x"></i></a>
            </div>
            <div class="panel-body">
                <form method="post" action="{{url('/account/report/filter/date')}}">
                    {{csrf_field()}}
                    <div class='col-md-3'>
                        <div class="form-group">
                            <div class='input-group date' id='StartDate' data-date="" data-date-format="dd-MM-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm">
                                <input type='text' class="form-control" placeholder="Date" id="SDate" name="date">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                            @if($errors->has('date'))
                                <span class="text-danger">{{$errors->first('date')}}</span>
                            @endif
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            <select name="typeDate" id="typeDate" class="form-control">
                                <option value="">Report Type</option>
                                <option value="m">Monthly</option>
                                <option value="y">Yearly</option>
                            </select>
                            @if($errors->has('typeDate'))
                                <span class="text-danger">{{$errors->first('typeDate')}}</span>
                            @endif
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            <input type="submit" name="submit" id="submit" value="Search" class="btn btn-default">
                        </div>
                    </div>

                </form>
                <div style="clear: both;"></div>
                <br>

                {{--Body report--}}
                    @if(count($transaction))
                        <div id="Balance">
                            <div style="text-align: center;" class="container-fluid">
                                <div style="color:rgba(37,143,183,0.85); line-height: 15px;">
                                    <p><b>CamSofts Company</b></p>
                                    <p><b>{{$reportType}} Balance Sheet</b></p>
                                    <p><b>{{\Carbon\Carbon::parse($endDate)->format('F d, Y')}}</b></p>

                                </div>
                            </div>
                            <table width="100%">
                                <tr>
                                    <td valign="top"​​​ width="50%">
                                        <div style="padding: 10px;">
                                            <p style="font-family: 'Calisto MT',Serif; color:rgba(19,164,183,0.85);line-height: 1px; font-size: 12px;"><b>ASSETS</b></p>
                                            <div style="height: 2px; background:rgba(28,146,183,0.85); margin-bottom: 20px;"></div>
                                            <table width="100%">
                                                <?php $totalA = 0; $id=0;?>
                                                @foreach($transaction as $t)
                                                    @if($t->typeaccount_id==2 || $t->typeaccount_id==1)
                                                            @if($t->chartaccount_id !=$id)
                                                                @php($runningB =\App\Transection::where('chartaccount_id',$t->chartaccount_id)->OrderBy('id','desc')->value('runningBalance'))
                                                                <?php $totalA = $totalA + $runningB;  $id = $t->chartaccount_id?>
                                                                <tr style="margin-left:50px;">
                                                                    <td style="padding-left: 2.5%;">{{ $t->chartaccount->description}}</td>
                                                                    <td style="text-align: right;">  {{$runningB <0 ? "(".substr($runningB,1).")" :$runningB }}</td>
                                                                </tr>
                                                            @endif
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <td style="padding-left:2.5%; line-height: 35px;"><b>Total Assets  </b></td>
                                                    <td style="text-align: right;">{{$totalA <0 ? "(".substr($totalA,1).")" : $totalA}}</td>
                                                </tr>
                                            </table>
                                            <br>
                                        </div>
                                    </td>
                                    <td valign="top">
                                        <div style="padding: 10px;">
                                            <p style="font-family: 'Calisto MT',Serif; color: rgba(19,164,183,0.85); line-height: 1px;font-size: 12px;"><b>LIABILITIES</b></p>
                                            <div style="height: 2px; background:rgba(28,146,183,0.85); margin-bottom: 20px;"></div>
                                            <table width="100%">
                                                <?php $totalL = 0; $id=0;?>
                                                @foreach($transaction as $l)

                                                        @if($l->typeaccount_id==3)
                                                            @php($RunningBL =\App\Transection::where('chartaccount_id',$l->chartaccount_id)->OrderBy('id','desc')->value('runningBalance'))
                                                            @if($l->chartaccount_id !=$id)
                                                                <?php $totalL = $totalL + $RunningBL;  $id = $l->chartaccount_id?>
                                                                <tr>
                                                                    <td style="padding-left:2.5%;">{{$l->chartaccount->description}}</td>
                                                                    <td style="text-align: right;">{{ $RunningBL<0 ? "(".substr($RunningBL,1).")" : $RunningBL }}</td>
                                                                </tr>
                                                            @endif
                                                        @endif
                                                @endforeach
                                                <tr>
                                                    <td style="padding-left:2.5%; line-height: 35px;"><b>Total Liabilities </b></td>
                                                    <td style="text-align: right;">{{$totalL<0 ? "(".substr($totalL,1).")" :$totalL}}</td>
                                                </tr>

                                            </table>
                                            <br>


                                            <p style="font-family: 'Calisto MT',Serif; color: rgba(19,164,183,0.85); line-height: 1px;font-size: 12px;"><b>STOCKHOLDERS'EQUITIES</b></p>
                                            <div style="height: 2px; background:rgba(28,146,183,0.85);margin-bottom: 20px;"></div>
                                            <table width="100%">
                                                <?php $totalE = 0; $id=0;?>
                                                @foreach($transaction as $e)
                                                    @if($e->typeaccount_id==4)
                                                        @php($RunningBE =\App\Transection::where('chartaccount_id',$e->chartaccount_id)->OrderBy('id','desc')->value('runningBalance'))
                                                        @if($t->chartaccount_id !=$id)
                                                            <?php $totalE = $totalE + $RunningBE;  $id = $e->chartaccount_id?>
                                                            <tr style="margin-left:50px;">
                                                                <td style="padding-left:2.5%;">{{$e->chartaccount->description}}</td>
                                                                <td style="text-align: right;">{{$RunningBE<0 ? "(".substr($RunningBE,1).")" : $RunningBE }}</td>
                                                            </tr>
                                                            @endif
                                                        @endif
                                                    @endforeach

                                                <tr>
                                                    <td style="padding-left:2.5%; line-height: 35px;"><b>Total Equities</b></td>
                                                    <td style="text-align: right;">{{$totalE<0 ? "(".substr($totalE,1).")" : $totalE}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 10px;">
                                        <table width="100%">
                                            <tr valign="bottom">
                                                <td style="color:#1f648b;"><b>Total Assets</b> </td>
                                                @if($totalL+$totalE !=$totalA)
                                                    <td style="text-align: right; color: red;"><b>$ {{$totalA<0 ? "(".substr($totalA,1).")" : $totalA}}</b></td>
                                                @else
                                                    <td style="text-align: right; color:#0d6aad;"><b>$ {{$totalA<0 ? "(".substr($totalA,1).")" : $totalA}}</b></td>
                                                @endif
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="padding: 0 10px;">
                                        <table width="100%">
                                            <tr valign="bottom">
                                                <td style="color: #1f648b; "><b>Total Equities & Liabilities</b></td>
                                                @if($totalL+$totalE !=$totalA)
                                                    <td style="text-align: right; color: red;"><b>$ {{$totalL+$totalE<0 ? "(".substr($totalL+$totalE,1).")" : $totalL+$totalE}}</b></td>
                                                @else

                                                    <td style="text-align: right; color:#0d6aad;"><b>$ {{$totalL+$totalE<0 ? "(".substr($totalL+$totalE,1).")" : $totalL+$totalE}}</b></td>
                                                @endif
                                            </tr>
                                        </table>
                                    </td>

                                </tr>
                            </table>
                        </div>
                    @else
                        <h4 style="margin-left: 1.7%; color: gray;">No data views</h4>
                    @endif
            </div>
            <div class="panel-footer">
                @if($transaction)
                    <a style="text-decoration:none;" href="#" class="btn-primary btn-sm" title="Print" id="btnPrintReport"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                    {{--<a style="text-decoration:none;" href="#" class="btn-success btn-sm" title="Excel" id="btnExportExcel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>--}}
                @endif
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('js/js.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/printThis.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
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
//            $('#EndDate').datetimepicker({
//                language:  'en',
//                weekStart: 1,
//                todayBtn:  1,
//                autoclose: 1,
//                todayHighlight: 1,
//                startView: 2,
//                minView: 2,
//                forceParse: 0
//            });
//            $("#StartDateate").on("dp.change", function (e) {
//                $('#StartDateate').data("dateTimePicker").minDate(e.date);
//            });
//            $("#EndDate").on("dp.change", function (e) {
//                $('#EndDate').data("dateTimePicker").maxDate(e.date);
//            });
        });
        $("#btnPrintReport").click(function () {
            $("#Balance").printThis({
                loadCSS:""
            });
        });
//        $("[id$=btnExportExcel]").click(function(e) {
//            window.open('data:application/vnd.ms-excel,' + encodeURIComponent( $('div[id$=Balance]').html()));
//            e.preventDefault();
//        });




    </script>

@endsection