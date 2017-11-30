@extends('layouts.admin')
@section('content')
<div class="container-fluid"><br>
  <div class="panel panel-default">
    <div class="panel-heading">
      SET VALUE FORM
    </div>
      <div class="panel panel-body">
        <div class="container-fluid table-responsive">
          <div class="row">
            <div class="col-lg-12">
              {!!Form::open(['action'=>'SetvariableController@save','method'=>'POST'])!!}
                {{csrf_field()}}
                          <div class="row">
                            <div class="col-lg-7">
                            <div class="form-group {{ $errors->has('cashonhand') ? ' has-error' : '' }}">
                                {!!Form::label('cashonhand','Cash on hand ',[])!!}
                                {!!Form::select('cashonhand',$chartaccounts,null,['class'=>'form-control','required'=>'true','placeholder'=>'--Select one--'])!!}
                              @if ($errors->has('cashonhand'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('cashonhand') }}</strong>
                                  </span>
                              @endif
                            </div>
                            </div>
                            <div class="col-lg-5">
                              <div class="form-group {{ $errors->has('drsign1') ? ' has-error' : '' }}">
                              {!!Form::label('drsign1',' Dr sign  ',[])!!}
                              {!!Form::select('drsign1', ['1' => '+1', '-1' => '-1'], null, ['class'=>'form-control','required'=>'true','placeholder'=> 'Value'])!!}
                              @if ($errors->has('drsign1'))
                                <span class="help-block">
                                  <strong>{{ $errors->first('drsign1') }}</strong>
                                </span>
                              @endif
                            </div>
                            </div>
                          </div>
                           <div class="row">
                            <div class="col-lg-7">
                            <div class="form-group {{ $errors->has('cost') ? ' has-error' : '' }}">
                                {!!Form::label('cost','Cost of goods sold ',[])!!}
                                {!!Form::select('cost',$chartaccounts,null,['class'=>'form-control','required'=>'true','placeholder'=>'--Select one--'])!!}
                              @if ($errors->has('cost'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('cost') }}</strong>
                                  </span>
                              @endif
                            </div>
                            </div>
                            <div class="col-lg-5">
                              <div class="form-group {{ $errors->has('drsign2') ? ' has-error' : '' }}">
                              {!!Form::label('drsign2',' Dr sign  ',[])!!}
                              {!!Form::select('drsign2', ['1' => '+1', '-1' => '-1'], null, ['class'=>'form-control','required'=>'true','placeholder'=> 'Value'])!!}
                              @if ($errors->has('drsign2'))
                                <span class="help-block">
                                  <strong>{{ $errors->first('drsign2') }}</strong>
                                </span>
                              @endif
                            </div>
                            </div>
                          </div>
                           <div class="row">
                            <div class="col-lg-7">
                            <div class="form-group {{ $errors->has('revenue') ? ' has-error' : '' }}">
                                {!!Form::label('revenue','Revenue ',[])!!}
                                {!!Form::select('revenue',$chartaccounts,null,['class'=>'form-control','required'=>'true','placeholder'=>'--Select one--'])!!}
                              @if ($errors->has('revenue'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('revenue') }}</strong>
                                  </span>
                              @endif
                            </div>
                            </div>
                            <div class="col-lg-5">
                              <div class="form-group {{ $errors->has('drsign3') ? ' has-error' : '' }}">
                              {!!Form::label('drsign3',' Dr sign  ',[])!!}
                              {!!Form::select('drsign3', ['1' => '+1', '-1' => '-1'], null, ['class'=>'form-control','required'=>'true','placeholder'=> 'Value'])!!}
                              @if ($errors->has('drsign3'))
                                <span class="help-block">
                                  <strong>{{ $errors->first('drsign3') }}</strong>
                                </span>
                              @endif
                            </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group">
                              <button type="submit" class="btn btn-sm btn-success submit"> Save close </button>
                              <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger"> Close </a>
                            </div>
                            </div>
                          </div>
              {!!Form::close()!!}
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
@stop
