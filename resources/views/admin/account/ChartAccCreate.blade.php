@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">
                Chart Of Account
            </div>
            <div class="panel-body">
                <div class="row">
                    <form action="{{url('account/create/acc/chart/stored')}}" method="post">
                        {{csrf_field()}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="AccType">Account Type</label>
                                {!! Form::select('typeaccountcode',$typeacc,null,['class'=>'form-control','placeholder'=>'Please choose one...']) !!}
                                {{--<select name="AccType" id="AccType" class="form-control">--}}
                                    {{--<option value="">Please choose one...</option>--}}
                                    {{--@foreach($accountType as $accType)--}}
                                        {{--<option value="{{$accType->id}}">{!! $accType->description !!}</option>--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                                @if($errors->has('typeaccountcode'))
                                    <span class="text-danger">{{ $errors->first('typeaccountcode') }}</span>
                                @endif
                                <span></span>
                            </div>
                            <div class="form-group">
                                <label for="description">COA Name</label>
                                <input type="text" name="description" id="description" class="form-control">
                                @if($errors->has('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="description">Sign</label>
                                {!! Form::select('sign',['dr'=>'Dr','cr'=>'Cr'],null,['class'=>'form-control','placeholder'=>'Please choose one...']) !!}
                                @if($errors->has('sign'))
                                    <span class="text-danger">{{ $errors->first('sign') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="valueSign">Values</label>
                                {!! Form::select('valueSign',['-1'=>'-1','1'=>'+1'],null,['class'=>'form-control','placeholder'=>'Please choose one...']) !!}
                                @if($errors->has('valueSign'))
                                    <span class="text-danger">{{ $errors->first('valueSign') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" id="submit" value="Create" class="btn btn-primary">
                                <input type="reset" value="Clear" class="btn btn-warning">
                            </div>

                        </div>
                    </form>
                    <div class="col-md-8">
                        <label for="">List Views</label>
                        <div id="AccType" style="overflow-x:auto">
                            <table class="table-edit" border="1px" width="100%">
                                <tr class="tr-edit">
                                    <th class="td-edit center">No</th>
                                    <th class="center padding">Account Code</th>
                                    <th class="padding">Account Type</th>
                                    <th class="padding">Description</th>
                                    <th class="center padding">Dr</th>
                                    <th class="center padding">Cr</th>
                                    <th class="center padding">Action</th>
                                </tr>
                                <?php $i=1;?>
                                @foreach($Chartaccount as $ChartAcc )
                                    @include('admin.account.popupChartAcc')
                                    <tr>
                                        <td class="center">{{$i++}}</td>
                                        <td class="center">{!! $ChartAcc->accountcode !!}</td>
                                        <td class="padding-td">{!! str_limit($ChartAcc->typeaccount->description,20) !!}</td>
                                        <td class="padding-td">{!! str_limit($ChartAcc->description,25) !!}</td>
                                        <td class="center">{!! $ChartAcc->drsign."00,000" !!}</td>
                                        <td class="center">{!! $ChartAcc->crsign."00,000" !!}</td>
                                        <td class="center">
                                            <a href="#" data-toggle="modal" data-target=".bs-{{$ChartAcc->id}}-modal-sm"><i class="fa fa-edit"></i></a>
                                            <a href="{{url('/account/delete',[$ChartAcc->id,"Chartaccount"])}}" style="color:#f85365; margin-left: 6px;"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <br>
                        </div>

                    </div>
                </div>
            </div>
            <div class="panel-footer">

            </div>
        </div>
    </div>

@endsection

@section('script')


@stop