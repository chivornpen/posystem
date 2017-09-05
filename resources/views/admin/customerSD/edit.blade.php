@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-lg-12">                
        <h4 class="page-header"><i class="fa fa-user" aria-hidden="true"></i> Edit Customer</h4>
    </div>
               <!-- /.col-lg-12 -->
</div>
<div>
  @include('nav.message')
</div>
    <div class="row">
      <div class="col-lg-12">
        {!!Form::model($customer,['action'=>['customerController@update',$customer->id],'method'=>'PATCH'])!!}
          {{csrf_field()}}
          <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        {!!Form::label('name','Customer Name : ',[])!!}
                        {!!Form::text('name',null,['class'=>'form-control','required'=>'true'])!!}
                          @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                          @endif
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group {{ $errors->has('contactNo') ? ' has-error' : '' }}">
                        {!!Form::label('contactNo','Contact No : ',[])!!}
                        {!!Form::number('contactNo',null,['class'=>'form-control','required'=>'true'])!!}
                          @if ($errors->has('contactNo'))
                            <span class="help-block">
                              <strong>{{ $errors->first('contactNo') }}</strong>
                            </span>
                          @endif
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group {{ $errors->has('location') ? ' has-error' : '' }}">
                        {!!Form::label('location','Location: ',[])!!}
                        {!!Form::text('location',null,['class'=>'form-control'])!!}
                          @if ($errors->has('location'))
                            <span class="help-block">
                                <strong>{{ $errors->first('location') }}</strong>
                            </span>
                          @endif
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group {{ $errors->has('channel_id') ? ' has-error' : '' }}">
                        {!!Form::label('channel_id','Channel Name :',[])!!}
                            {!!Form::select('channel_id',[null=>'---Please select a channel name---']+$channels,null,['class'=>'form-control'])!!}
                          @if ($errors->has('channel_id'))
                            <span class="help-block">
                              <strong>{{ $errors->first('channel_id') }}</strong>
                            </span>
                          @endif
                      </div>
                    </div>
                  </div>
          <div class="well well-sm">
            <button type="submit" class="btn btn-success"> Update </button>
            <a href="{{ url()->previous() }}" class="btn btn-info pull-right">Back</a>
          </div>
          
        {!!Form::close()!!}
      </div>
    </div>
@stop