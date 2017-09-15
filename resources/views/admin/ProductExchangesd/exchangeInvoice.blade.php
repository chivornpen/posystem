@extends('layouts.admin')
@section('content')
        <div class="container-fluid">
            <br><br>
            <div class="panel panel-default">
                <div class="panel-heading">Create Exchange Invoice</div>
                <div class="panel panel-body table-responsive">
                    <div class="row">
                        <div class="col-lg-12">
                            <select name="invoice" id="invoice" class="form-control" onchange="listInvoice()">
                                <option value="0">Please select one</option>
                                @foreach($exchange as $ex)
                                    @if($ex->purchaseordersd_id=="")
                                        <option value="{{$ex->id}}">{{"CAM-IN-".sprintf('%06d',$ex->stockoutsd->purchaseordersd_id)}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div id="viewXchange">

                    </div>
                </div>
            </div>
        </div>
@endsection

@section('script')
    <script type="text/javascript">
        function listInvoice() {
            var exId = $("#invoice").val();

                $("#invoice").css('border','');
                $.ajax({
                    type: 'get',
                    url: "{{url('listInvoiceExchange')}}"+"/"+exId,
                    dataType: 'html',
                    success:function (data) {
                        $("#viewXchange").html(data);

                        $(document).ready(function () {
                            $("#exchangeInvoice").DataTable({});
                        });
                    },
                    error:function (error) {
                        console.log(error);
                    }
                });
        }

        function createInvoice(id) {
            $.ajax({
               type: 'get',
                url: "{{url('createNewInvoice')}}"+"/"+id,
                dataType: 'html',
                success:function (data) {
                    location.reload();
                    alert('Created successfully....');
                },
                error:function (error) {
                    console.log(error);
                }
            });
        }


    </script>
@endsection