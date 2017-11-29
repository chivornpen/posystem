@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">
                Export Request
            </div>
            <div class="panel-body">
                {!! Form::open(['method'=>'POST','action'=>'RequestproController@exportRequestPro']) !!}

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <select name="requestNumber" id="requestNumber" class="form-control" onchange="showReDetail()">
                                    <option value="0">Request Numbers</option>
                                    @foreach($requstPro as $re)
                                        <option value="{{$re->id}}">{!! "REQ-".sprintf('%06d',$re->id) !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="showRequestDetail" style="display: none;">

                    </div>
                {!! Form::close() !!}
            </div>
            <div class="panel-footer">

            </div>
        </div>
    </div>
@endsection

<script type="text/javascript">
    function showReDetail() {
        var request_id = $('#requestNumber').val();
        if(request_id!=0){
            $.ajax({
               type:'get',
                url: "{{url('export/request/product/detail/')}}"+"/"+request_id,
                dataType: 'html',
                success:function (data) {
                    $('#showRequestDetail').html(data).fadeIn('slow');
                },
                error:function (error) {
                    console.log(error);
                }
            });

        }else {
            $('#showRequestDetail').hide();
        }
    }
</script>


@section('script')


@endsection