@extends('layouts.admin')
@section('content')
<div class="container-fluid"><br>
  <div class="panel panel-default">
    <div class="panel-heading">
      INCOME STATEMENT
      <a href="{{route('dashbords.index') }}" class="btn btn-danger btn-xs pull-right" aria-label="Close">X</a>
    </div>

      <div class="panel panel-body">
        <div class="container-fluid table-responsive">
          <form method="post" action="{{url('/admin/income/report/filter/date')}}">
                    {{csrf_field()}}
                    <div class='col-md-3'>
                        <div class="form-group">
                            <div class='input-group date' id='StartDate' data-date="" data-date-format="dd-MM-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm">
                                <input required type='text' class="form-control" placeholder="Date" id="SDate" name="date">
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
                            <select required name="typeDate" id="typeDate" class="form-control">
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
                    @if(count($transections))
                        <div id="Balance">
                            <div style="text-align: center;" class="container-fluid">
                                <div style="color:rgba(37,143,183,0.85); line-height: 15px;">
                                    <p><b>CamSofts Company</b></p>
                                    <p><b>{{$reportType}} Income Statement</b></p>
                                    <p><b>{{\Carbon\Carbon::parse($endDate)->format('F d, Y')}}</b></p>

                                </div>
                            </div>
                            <table width="100%">
                                <tr>
                                    <td valign="top"​​​ width="50%">
                                        <div style="padding: 10px;">
                                            <p style="font-family: 'Calisto MT',Serif; color:rgba(19,164,183,0.85);line-height: 1px; font-size: 12px;"><b>REVENUES</b></p>
                                            <div style="height: 2px; background:rgba(28,146,183,0.85); margin-bottom: 20px;"></div>
                                            <table width="100%">
                                                <?php $id=0;$totalRe=0; ?>
                                                  @foreach($transections as $tra)
                                                  <tr style="margin-left:50px;">
                                                    @if($tra->typeaccount_id==5)
                                                      @if($tra->chartaccount_id!=$id)
                                                        @php($RunningBL =\App\Transection::where('chartaccount_id',$tra->chartaccount_id)->OrderBy('id','desc')->value('runningBalance'))
                                                        <?php $totalRe = $totalRe + $RunningBL;  $id=$tra->chartaccount_id;?>
                                                        <td style="padding-left: 2.5%;">{{$tra->chartaccount->description}}</td>
                                                        <td style="text-align: right;">{{ $RunningBL<0 ? "(".substr($RunningBL,1).")" : $RunningBL }}</td>
                                                      @endif
                                                    @endif
                                                  </tr>
                                                  @endforeach
                                            </table>
                                            <br>
                                        </div>
                                    </td>
                                    <td valign="top">
                                        <div style="padding: 10px;">
                                            <p style="font-family: 'Calisto MT',Serif; color: rgba(19,164,183,0.85); line-height: 1px;font-size: 12px;"><b>EXPENSES</b></p>
                                            <div style="height: 2px; background:rgba(28,146,183,0.85); margin-bottom: 20px;"></div>
                                            <table width="100%">
                                                <?php $id=0; $totalEx=0; ?>
                                                  @foreach($transections as $tra)
                                                  <tr style="margin-left:50px;">
                                                    @if($tra->typeaccount_id==6)
                                                      @if($tra->chartaccount_id!=$id)
                                                        @php($RunningBL =\App\Transection::where('chartaccount_id',$tra->chartaccount_id)->OrderBy('id','desc')->value('runningBalance'))
                                                        <?php $totalEx = $totalEx + $RunningBL;  $id=$tra->chartaccount_id;?>
                                                        <td style="padding-left: 2.5%;">{{$tra->chartaccount->description}}</td>
                                                        <td style="text-align: right;">{{ $RunningBL<0 ? "(".substr($RunningBL,1).")" : $RunningBL }}</td>
                                                      @endif
                                                    @endif
                                                  </tr>
                                                  @endforeach
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                  <td style="padding: 0 10px;">
                                        <table width="100%">
                                            <tr valign="bottom">
                                                <td style="color:#1f648b;"><b>Gross Profit</b> </td>
                                                <td style="text-align: right; color: #0d6aad;"><b>{{ $totalRe<0 ? "(".substr($totalRe,1).")" : $totalRe }}</b></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="padding: 0 10px;">
                                        <table width="100%">
                                            <tr valign="bottom">
                                                <td style="color:#1f648b;"><b>Total Expense</b> </td>
                                                    <td style="text-align: right; color:#0d6aad;"><b>{{ $totalEx<0 ? "(".substr($totalEx,1).")" : $totalEx }}</b></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                  <?php $netIncome = $totalRe-$totalEx; ?>
                                  @if($netIncome>0)
                                    <td style="color:#5DC466;padding: 40px 10px;"><b>Net Income</b></td>
                                    <td style="text-align: right; color:#5DC466; padding: 40px 10px"><b>$ {{ $netIncome<0 ? "(".substr($netIncome,1).")" : $netIncome }}</b></td>
                                  @else
                                  <td style="color:red;padding: 40px 10px;"><b>Net Income</b></td>
                                  <td style="text-align: right; color:red; padding: 40px 10px"><b>$ {{ $netIncome<0 ? "(".substr($netIncome,1).")" : $netIncome }}</b></td>
                                  @endif
                                </tr>
                            </table>
                        </div>
                    @else
                        <h4 style="margin-left: 1.7%; color: gray;">No data views</h4>
                    @endif
            </div>
            {{----end body----}}
            </div>
            <div class="panel-footer">
                @if($transections)
                    <a style="text-decoration:none;" href="#" class="btn-primary btn-sm" title="Print" id="btnPrintReport"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                    {{--<a style="text-decoration:none;" href="#" class="btn-success btn-sm" title="Excel" id="btnExportExcel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>--}}
                @endif
        </div>
      </div>
  </div>
</div>
@stop
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
        });
        $("#btnPrintReport").click(function () {
            $("#Balance").printThis({
                loadCSS:""
            });
        });
  </script>
@stop