<div class="modal fade bs-{{$accType->id}}-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div style="width: 30%; margin: 50px auto;">
        <div class="panel panel-default">
            <div class="panel-heading">
                Update
            </div>
            <div class="panel-body">

             {!! Form::model($accType,['method'=>'PATCH','action'=>['Accounting@update',$accType->id]]) !!}
                {{csrf_field()}}
                <input type="hidden" name="modalName" value="AccountType">
                <div class="form-group">
                    <label for="AccTypeCode">Type Account Code</label>
                    {!! Form::number('typeaccountcode',null,['class'=>'form-control']) !!}
                    {{--<input type="number" name="AccTypeCode" id="AccTypeCode" class="form-control">--}}
                </div>
                <div class="form-group">
                    <label for="Edescription">Description</label>
                    {!! Form::text('description',null,['class'=>'form-control','readOnly'=>true]) !!}
                    {{--<input type="text" name="Edescription" id="Edescription" class="form-control">--}}
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