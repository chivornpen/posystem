@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <br><br>
        <div class="panel panel-default">
            <div class="panel-heading">Create Invoice Product Return</div>
            <div class="panel panel-body table-responsive">
                <div class="row">
                    <div class="col-lg-6">
                        <select name="Rinvoice" id="Rinvoice" class="form-control">
                            <option value="0">Please select Invoice</option>
                            @foreach($productReturn as $re)
                                @if($re->status!="a")
                                    <option value="{{$re->id}}">{{"CAM-IN-".sprintf('%06d',$re->stockout->purchaseorder_id)}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <select name="paid" id="paid" class="form-control" onchange="productInvoice()">
                            <option value="0">Please select one</option>
                            <option value="1">Company paid</option>
                            <option value="2">Customer paid</option>
                        </select>
                    </div>
                </div>
                <br>
                <br>
                <div id="viewProReturn">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
            function productInvoice() {
                var returnId = $("#Rinvoice").val();
                var status = $("#paid").val();
                if(returnId!=0 && status!=0){
                    $.ajax({
                        type: 'get',
                        url: "{{url('/invoicePo/showcontent/view')}}"+"/"+returnId+"/"+status,
                        dataType: 'html',
                        success:function (data) {
                            $("#viewProReturn").html(data);
                            $(document).ready(function () {
                                $("#ProductReturn").DataTable({});
                            });

                        },
                        error:function (error) {
                            console.log(error);
                        }
                    });
                }else {
                    $.ajax({
                        type: 'get',
                        url: "{{url('/invoicePo/showcontent/view')}}"+"/"+0+"/"+0,
                        dataType: 'html',
                        success:function (data) {
                            $("#viewProReturn").html(data);
                            $(document).ready(function () {
                                $("#ProductReturn").DataTable({});
                            });

                        },
                        error:function (error) {
                            console.log(error);
                        }
                    });
                }
            }

            function ProductReturCreate(returnId) {
                var status = $("#paid").val();
                $.ajax({
                   type: 'get',
                    url: "{{url('invoicePo/ProductReturn/invoice/create')}}"+"/"+returnId+"/"+status,
                    dataType: 'html',
                    success:function (data) {
                        location.reload();
                    },
                    error:function (error) {
                        console.log(error);
                    }
                });
            }
    </script>
@endsection