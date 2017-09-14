@extends('layouts.admin')
@section('content')
    <div>
        @include('nav.message')
    </div>
    <div class="container-fluid">
        <br><br>
        <div class="panel panel-default">
            {{--Create Users--}}
            <div class="panel-heading">Export</div>
            <div class="panel panel-body">
                <div class="container-fluid">
                    <div class="row">
                        {!!Form::open(['action'=>'StockoutSdController@store','method'=>'post'])!!}
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {!! Form::label('stockoutdate','Stock Out Date') !!}
                                    {!! Form::date('date',\Carbon\Carbon::now(),['class'=>'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {!! Form::label('InvoiceDate','Invoice Date') !!}
                                    {!! Form::date('invoiceDate',null,['class'=>'form-control','id'=>'invoiceDate','readOnly'=>true]) !!}
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {!! Form::label('customerName','Customer Name') !!}
                                    {!! Form::text('customerName',null,['class'=>'form-control','id'=>'customerName','readOnly'=>true]) !!}
                                </div>
                            </div>
                            {{--<div class="col-lg-12">--}}
                                {{--<div class="form-group">--}}
                                    {{--{!! Form::label('location','Location') !!}--}}
                                    {{--{!! Form::text('location',null,['class'=>'form-control','id'=>'province','readOnly'=>true]) !!}--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {!! Form::label('invoiceN','Invoice Number ') !!}
                                        <select name="invoiceN" id="invoiceN" class="form-control" required onchange="InvNChange()">
                                            <option value="">Please select</option>
                                            @foreach($invoices as $invoice)
                                              @foreach($invoice as $inv)
                                                <option value="{{$inv->id}}">{{"CAM-IN-" . sprintf('%06d',$inv->id)}}</option>
                                              @endforeach
                                            @endforeach
                                        </select>
                                        @if($errors->has('invoiceN'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('invoiceN') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {!! Form::submit('Submit',['class'=>'btn btn-success btn-sm']) !!}
                                    <a href="{{url('admin/dashbords')}}" class="btn btn-danger btn-sm">Cancel</a>
                                </div>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')
    <script type="text/javascript">
            function InvNChange() {
               var poid = $("#invoiceN").val();
               if(poid ==0){
                  $("#customerName").val(null);
                  $("#invoiceDate").val(null);
               }
               if(poid!=0){
                   $.ajax({
                       type : 'get',
                       url : "{{url('/getIdPoSd')}}"+"/"+poid,
                       dataType: 'json',
                       success:function (data) {
                           console.log(data);
                           $("#customerName").val(data['userName']);

                           $("#invoiceDate").val(data['invoiceDate']);
                       },
                       error:function (error) {
                         console.log(error);
                       }

                   });
               }

            }
    </script>

@stop
