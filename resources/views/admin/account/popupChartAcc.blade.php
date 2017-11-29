<div class="modal fade bs-{{$ChartAcc->id}}-modal-sm" tabindex="-1" role="dialog" ariaplaceholder="mySmallModalLabel">
    <div style="width: 30%; margin: 50px auto;">
        <div class="panel panel-default">
            <div class="panel-heading">
                Update
            </div>
            <div class="panel-body">

                {!! Form::model($ChartAcc,['method'=>'PATCH','action'=>['Accounting@update',$ChartAcc->id]]) !!}
                    {{csrf_field()}}
                    <input type="hidden" name="modalName" value="AccountChart">
                    <div class="form-group">
                        <label for="typeaccount_id">Type Account Code</label>
                        {!! Form::select('typeaccount_id',$typeacc,null,['class'=>'form-control','required'=>true]) !!}
                        {{--<input type="number" name="AccTypeCode" id="AccTypeCode" class="form-control">--}}
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        {!! Form::text('description',null,['class'=>'form-control','required'=>true]) !!}
                        {{--<input type="text" name="Edescription" id="Edescription" class="form-control">--}}
                    </div>
                    <div class="form-group">
                        <label for="drsign">Dr Sign</label>
                        {!! Form::number('drsign',null,['class'=>'form-control','required'=>true]) !!}
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary btn-sm" value="Update">
                        <a href="#" data-dismiss="modal" class="btn btn-danger btn-sm">Close</a>
                    </div>

                    {{--{!! Form::close() !!}--}}
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>