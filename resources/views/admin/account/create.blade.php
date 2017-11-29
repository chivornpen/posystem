@extends('layouts.admin')

@section('content')
    <br>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                Accountant
            </div>
            <div class="panel-body">

                {!! Form::open(['method'=>'POST','action'=>'Accounting@create']) !!}
                    {{--<form action="{{url('/account/create/booking/create')}}" method="post">--}}
                        {{csrf_field()}}

                        <div class="row">
                            {{--<div class="form-group">--}}
                            {{--<label for="batch">Batch Code</label>--}}
                            {{--<input type="hidden" name="batch" id="batch" class="form-control">--}}
                            {{--</div>--}}

                            {{--<div class="form-group">--}}
                            {{--<label for="Transitions">Transitions Code</label>--}}
                            {{--<input type="hidden" name="Transitions" id="Transitions" class="form-control">--}}
                            {{--</div>--}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="account_type">Account Type </label>
                                    {!! Form::select('account_type',$typeacc,null,['class'=>'form-control','id'=>'account_type','placeholder'=>'Please choose one...','onchange'=>'selectChartAcc()']) !!}
                                    @if($errors->has('account_type'))
                                        <span class="text-danger">{{$errors->first('account_type')}}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="chart_account">Chart Of Account</label>
                                    {!! Form::select('chart_account',[],null,['class'=>'form-control', 'id'=>'chart_account','placeholder'=>'Please choose one...','onchange'=>'getSign()']) !!}
                                    @if($errors->has('chart_account'))
                                        <span class="text-danger">{{$errors->first('chart_account')}}</span>
                                    @endif
                                    {{--<select name="chart_account" id="chart_account" class="form-control">--}}
                                        {{--<option value="0">Please choose one...</option>--}}
                                        {{--<option value="1">Current Asset</option>--}}
                                        {{--<option value="2">Cash</option>--}}
                                    {{--</select>--}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sign">Sign (<small> Dr/Cr </small>)</label>
                                    <select name="sign" id="sign" class="form-control">
                                        <option value="">Please choose one...</option>
                                        <option value="drsign">Dr</option>
                                        <option value="crsign">Cr</option>
                                    </select>
                                    @if($errors->has('sign'))
                                        <span class="text-danger">{{$errors->first('sign')}}</span>
                                    @endif
                                </div>

                                <input type="hidden" name="drsign" id="drsign">
                                <input type="hidden" name="crsign" id="crsign">

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount">Amount <span style="color: red">($)</span></label>
                                    <input type="number" name="amount" id="amount"  min="0" class="form-control">
                                    @if($errors->has('amount'))
                                        <span class="text-danger">{{$errors->first('amount')}}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="currency">Currency</label>
                                    <select name="currency" id="currency" class="form-control">
                                        <option value="">Please choose one...</option>
                                        <option value="BAHT">BAHT</option>
                                        <option value="KHR">KHR</option>
                                    </select>
                                    @if($errors->has('currency'))
                                        <span class="text-danger">{{$errors->first('currency')}}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exchange_rate">Exchange Rate</label>
                                    <input type="number" name="exchange_rate" id="exchange_rate" class="form-control">
                                    @if($errors->has('exchange_rate'))
                                        <span class="text-danger">{{$errors->first('exchange_rate')}}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{--<div class="col-md-4">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label for="brand_code">Brand Code</label>--}}
                                    {{--<input type="number" name="brand_code" id="brand_code" class="form-control">--}}
                                    {{--@if($errors->has('brand_code'))--}}
                                        {{--<span class="text-danger">{{$errors->first('brand_code')}}</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" name="description" id="description" class="form-control">
                                    @if($errors->has('description'))
                                        <span class="text-danger">{{$errors->first('description')}}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::submit('Book',['class'=>'btn btn-primary btn-sm']) !!}
                                    {!! Form::reset('Reset',['class'=>'btn btn-warning btn-sm']) !!}
                                </div>

                            </div>
                        </div>
                    {{--</form>--}}
                {!! Form::close() !!}
                @if(count($view))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">Total Dr ($)</div>
                                    <input type="text" value="{{$totalDr}}" class="form-control" id="total" placeholder="Total">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">Total Cr ($)</div>
                                    <input type="text" value="{{$totalCr}}" class="form-control" id="total" placeholder="Total">
                                </div>
                            </div>
                                <div class="form-group">
                                    <label for="list_view">List Views</label>
                                    <div  id="list_view" style="overflow-x: scroll">
                                            <table class="table-edit" border="1px" width="100%">
                                                <tr class="tr-edit">
                                                    <th class="td-edit padding">Account Code</th>
                                                    <th class="center padding">Total Dr</th>
                                                    <th class="center padding">Total Cr</th>
                                                    <th class="center padding">Action</th>
                                                </tr>

                                                @foreach($view as $v)
                                                    <tr>
                                                        <td class="padding-td">{{$v->chartaccount->accountcode}}</td>
                                                        <td class="center">{{$v->drAmt}}</td>
                                                        <td class="center">{{$v->crAmt}}</td>
                                                        <td class="center">
                                                            <a href="{{url('/account/dTransition',$v->id)}}" style="color:#f85365; margin-left: 6px;"><i class="fa fa-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        <br>
                                        <div class="form-group">
                                            <a href="{{url('/account/submitBook')}}"  class="btn btn-sm btn-success">Save Close</a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                @else
                    {{--<h4>No result views</h4>--}}
                @endif
            </div>
            <div class="panel-footer">

            </div>

        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function selectChartAcc() {
            var id = $('#account_type').val();
            $.ajax({
                type: 'get',
                url: "{{url('/account/selectChartAcc')}}"+"/"+id,
                dataType: 'json',
                success:function (data) {
                    $('#chart_account').empty();
                    var serialnumber="<option value=''>Please choose one...</option>";
                    $('#chart_account').append(serialnumber);
                    data.map(function (item) {
                        serialnumber="<option value="+ item.id+">"+item.description+' '+ '('+item.accountcode+')'+"</option>";
                        $('#chart_account').append(serialnumber);
                    });
                    console.log(data);
                },
                error:function (error) {
                    console.log(error);
                }
            });
        }
        function getSign() {
            var id = $('#chart_account').val();
            $.ajax({
                type: 'get',
                url: "{{url('/account/get/sign')}}"+"/"+id,
                dataType:'json',
                success:function (data) {
                    $('#drsign').val(data[0]['drsign']);
                    $('#crsign').val(data[0]['crsign']);
                },
                error:function (error) {
                    console.log(error);
                }

            });
        }
    </script>
@endsection