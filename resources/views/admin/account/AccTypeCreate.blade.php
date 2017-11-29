@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">
                Account Type
            </div>
            <div class="panel-body">
                <div class="row">
                    {{--<form action="{{url('/account/create/acc/type/stored')}}" method="post">--}}
                        {{--{{csrf_field()}}--}}
                        {{--<div class="col-md-4">--}}
                            {{--<div class="form-group">--}}
                                {{--<label for="TypeAccountCode">Type Account Code</label>--}}
                                {{--<input type="number" name="TypeAccountCode" id="TypeAccountCode" class="form-control" >--}}
                                {{--@if($errors->has('TypeAccountCode'))--}}
                                    {{--<span class="text-danger">{{$errors->first('TypeAccountCode')}}</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                                {{--<label for="description">Description</label>--}}
                                {{--<input type="text" name="des" id="description" class="form-control" >--}}
                                {{--@if($errors->has('des'))--}}
                                    {{--<span class="text-danger">{{$errors->first('des')}}</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                                {{--<input type="submit" name="submit" id="submit" value="Create" class="btn btn-primary">--}}
                                {{--<input type="reset" value="Clear" class="btn btn-warning">--}}
                            {{--</div>--}}

                        {{--</div>--}}
                    {{--</form>--}}
                    <div class="col-md-12">
                        <label for="">List Views</label>
                        <div id="AccType" style="overflow-x:auto">
                            <table class="table-edit" border="1px" width="100%">
                                <tr class="tr-edit">
                                    <th class="td-edit center">No</th>
                                    <th class="center padding">Type Account Code</th>
                                    <th class=" padding">Description</th>
                                    <th class="center padding">Action</th>
                                </tr>

                                <?php $i=1; ?>
                                @foreach($accountType as $accType)
                                    @include('admin.account.popup')
                                    <tr>
                                        <td class="center">{{$i++}}</td>
                                        <td class="center">{{$accType->typeaccountcode}}</td>
                                        <td class="padding-td">{{$accType->description}}</td>
                                        <td class="center">
                                            {{--<button data-toggle="modal" data-target=".bs-{{$accType->id}}-modal-sm"><i class="fa fa-edit"></i></button>--}}
                                            <a href="#" data-toggle="modal" data-target=".bs-{{$accType->id}}-modal-sm"><i class="fa fa-edit"></i></a>
                                            {{--<a href="{{url('/account/delete',[$accType->id,"Typeaccount"])}}" style="color:#f85365; margin-left: 6px;"><i class="fa fa-trash"></i></a>--}}
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
