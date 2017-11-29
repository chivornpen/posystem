@extends('layouts.admin')
@section('content')
    <br>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                TRIAL BALANCE
                <a href="{{url('admin/dashbords')}}"  style="float: right;" class="btn btn-danger btn-xs"><i class="fa fa-close"></i></a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <form method="post" action="{{url('/account/report/trial/balance/filter')}}">
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
                </div>
                <div style="clear: both;"></div>
                <br>
                @if(count($transaction))
                    <div id="trialBalance">
                        <div style="text-align: center;" class="container-fluid">
                            <div style="color:rgba(37,143,183,0.85); line-height: 15px;">
                                <p><b>CamSofts Company</b></p>
                                <p><b>{{$reportType}} Trial Balance</b></p>
                                <p><b>{{\Carbon\Carbon::parse($endDate)->format('F d, Y')}}</b></p>

                            </div>
                        </div>
                        <div style="width: 80%; margin: 0 auto;">
                            <table width="100%">
                                <tr>
                                    <td style="padding: 7px;"><b>Acc Code</b></td>
                                    <td style="padding: 7px;"><b>Account</b></td>
                                    <td style="padding: 7px; text-align: center;"><b>Debits</b></td>
                                    <td style="padding: 7px;text-align: center;"><b>Credits</b></td>
                                </tr>
                                <?php $id=0; $totalDr=0; $totalCr=0; $data=array();?>
                                @foreach($transaction as $t)
                                    @if($t->chartaccount_id !=$id)
                                        <?php $id = $t->chartaccount_id; ?>
                                            @php($RunningB =\App\Transection::where('chartaccount_id',$t->chartaccount_id)->OrderBy('id','desc')->value('runningBalance'))
                                            @if($t->typeaccount_id ==1|| $t->typeaccount_id ==2 ||$t->typeaccount_id ==6 ||$t->typeaccount_id ==8)
                                                <?php $data[]=['AcountCode'=>$t->chartaccount->accountcode,'AccountName'=>$t->chartaccount->description,'Dr'=>$RunningB,'Cr'=>'0']?>
                                            @else
                                                <?php $data[]=['AcountCode'=>$t->chartaccount->accountcode,'AccountName'=>$t->chartaccount->description,'Dr'=>'0','Cr'=>$RunningB]?>
                                            @endif
                                    @endif

                                @endforeach
                                <?php asort($data);?>
                                @foreach($data as $d)
                                    <tr>
                                        <?php $totalDr=$totalDr+$d['Dr']; $totalCr=$totalCr+$d['Cr']; ?>
                                        <td style="padding: 3px 7px; color: #2F3133;">{{$d['AcountCode']}}</td>
                                        <td style="padding: 3px 7px; color: #2F3133;">{{$d['AccountName']}}</td>
                                            @if($d['Dr']!=0)
                                                <td style="padding: 3px 7px; color: #2F3133; width:20%; text-align:center;">{{$d['Dr']}}</td>
                                            @else
                                                <td style="padding: 3px 7px; color: #2F3133; width:20%; text-align:center;">{{""}}</td>
                                            @endif
                                            @if($d['Cr']!=0)
                                                <td style="padding: 3px 7px;color: #2F3133;width:20%; text-align:center;">{{$d['Cr']}}</td>
                                            @else
                                                <td style="padding: 3px 7px; color: #2F3133; width:20%; text-align:center;">{{""}}</td>
                                            @endif
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: center; padding: 15px 0; color: rgba(183,52,24,0.85);"><b>$ {{$totalDr}}</b></td>
                                    <td style="text-align: center;color:rgba(183,52,24,0.85);"><b>$ {{$totalCr}}</b></td>
                                </tr>
                            </table>
                        </div>

                    </div>
                @else
                    <h4 style="color: gray;">No data views</h4>
                @endif


            </div>
            <div class="panel-footer">
                <a href="#" class="btn btn-primary btn-sm" id="printTrial"><i class="fa fa-print"></i></a>
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
        $("#printTrial").click(function () {
            $("#trialBalance").printThis({
                loadCSS:""
            });
        });
    </script>

@endsection