@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">
                Exchange Product
            </div>
            <div class="panel-body table-responsive">
                {!! Form::open(['method'=>'post', 'action'=>'ProductExchange@store'])!!}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <select name="invN" id="invN" class="form-control" onchange="InvoiceView()">
                                    <option value="0">Please select</option>
                                    @foreach( $stockout as $invN)
                                        <option value="{{$invN->purchaseorder_id}}">{!! "CAM-IN-" . sprintf('%06d',$invN->purchaseorder_id) !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
                <div id="viewInvoice" style="margin-right: 1%;">

                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">


        var impId =0;
        var proId=0;
        var exp=0;
        var Qty=0;
        var stId = 0;
        function InvoiceView()
        {
            var InvN = $("#invN").val();
                $.ajax({
                    type: 'get',
                    url: "{{url('/exchange/showRecord')}}"+"/"+InvN,
                    dataType: 'html',
                    success:function (data) {
                        $("#viewInvoice").html(data);

                        $(document).ready(function() {
                            $('#Exchange').DataTable({});
                        });
                    },
                    error:function (error) {
                        console.log(error);
                    }
                });

        }

        function Exchange(importId, productID, expd, qty,stockoutId) {
           impId =importId;
           proId=productID;
           exp=expd;
           Qty=qty;
           stId = stockoutId;

           document.getElementById('exQty').value=Qty;

        }
        function checkQty() {

            var qty = $("#exQty").val();

            if( parseInt(Qty) < qty || qty<=0){
                $("#exQty").css('border','1px solid red')
            }else {
                $("#exQty").css('border','');
            }

        }
        function SaveRecord() {

            var qty = $("#exQty").val();
            var err = "";
            if( parseInt(Qty) < qty || qty<=0){
                $("#exQty").css('border','1px solid red');
                err+="have error";
            }else {
                $("#exQty").css('border','');
            }

            if(qty==""){
                $("#exQty").css('border','1px solid red');
                err+="have error";
            }else {
                $("#exQty").css('border','');
            }
            if(err==""){
                $.ajax({
                    type: 'get',
                    url: "{{url('/exchange/save')}}"+"/"+impId+"/"+proId+"/"+qty+"/"+exp+"/"+stId,
                    dataType: 'html',
                    success:function (data) {
                        $("#viewInvoice").html(data);
                        $(document).ready(function() {
                            $('#Exchange').DataTable({});
                        });
                        $('.modal-backdrop').remove();
                    },
                    error:function (error) {
                        console.log(error);
                    }
                });
            }
        }


    </script>


@endsection