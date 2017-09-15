@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">
                Return Product
            </div>
            <div class="panel-body table-responsive">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <select name="return" id="returnInvId" onchange="viewInvoice()" class="form-control">
                                <option value="0">Please select</option>
                                @foreach( $stockout as $invN)
                                    <option value="{{$invN->purchaseorder_id}}">{!! "CAM-IN-" . sprintf('%06d',$invN->purchaseorder_id) !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <select name="returnBy" id="returnBy" class="form-control" onchange="viewInvoice()">
                            <option value="0">Return By</option>
                            @foreach($user as $u)
                                <option value="{{$u->id}}">{!! $u->nameDisplay !!}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div id="ReturnInvoice">

                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        var returnBy=0;
        var impId =0;
        var proId=0;
        var exp=0;
        var Qty=0;
        var stId = 0;
        var err="";
        var InvN=0;
            function viewInvoice() {
                var InvN = $("#returnInvId").val();
                var returnBy =$("#returnBy").val();
                if(returnBy!=0 && InvN!=0){
                    $("#returnBy").css('border','');
                    $.ajax({
                        type: 'get',
                        url: "{{url('/return/VUinvoice/')}}"+"/"+InvN,
                        dataType: 'html',
                        success:function (data) {
                            $("#ReturnInvoice").html(data);

                            $(document).ready(function () {
                                $("#returnInvoice").DataTable({});
                            })
                        },
                        error:function (error) {
                            console.log(error);
                        }
                    });
                }else{

                    $.ajax({
                        type: 'get',
                        url: "{{url('/return/VUinvoice/')}}"+"/"+0,
                        dataType: 'html',
                        success:function (data) {
                            $("#ReturnInvoice").html(data);

                            $(document).ready(function () {
                                $("#returnInvoice").DataTable({});
                            })
                        },
                        error:function (error) {
                            console.log(error);
                        }
                    });
                }
            }
        function ckReturnall() {//checkbox return all
            $('#ckreturnAll').change(function () {
                if(this.checked){
                    console.log($("#ckreturnAll").val());
                    $("#btnReturnAll").css('display','');
                    $("#returnInvoice").click(false);
                }else{
                    viewInvoice();
                    $("#btnReturnAll").css('display','none');
                }
            });
        }

        function ReturnAll(id) {//Save data when check checkbox return all
                var userId=$("#returnBy").val();
                $.ajax({
                   type: 'get',
                    url: "{{url('return/ReturnAll/')}}"+"/"+id+"/"+userId,
                    dataType: 'html',
                    success:function (data) {
                       $("#ReturnInvoice").html(data);
                    },
                    error:function (error) {
                        console.log(error);
                    }
                });
        }
        function Return(importId, productID, expd, qty,stockoutId) {
            returnBy = $("#returnBy").val();
            $("#returnQty").css('border','');
                impId =importId;
                proId=productID;
                exp=expd;
                Qty=qty;
                stId = stockoutId;
                InvN = $("#returnInvId").val();
                document.getElementById("returnQty").value=Qty;
                //alert('Messsage: '+importId+"\n"+productID+"\n"+expd+"\n"+qty+"\n"+stockoutId);
        }
        function checkQty() {
            var qty = $("#returnQty").val();

            if( parseInt(Qty) < qty || qty<=0){
                $("#returnQty").css('border','1px solid red');
                err="hass error here";
            }else {
                $("#returnQty").css('border','');
                err="";
            }

        }
        function SaveReturn() {
            var qty = $("#returnQty").val();
            if(err==""){
                $.ajax({
                    type: 'get',
                    url: "{{url('return/save/one')}}"+"/"+stId+"/"+Qty+"/"+qty+"/"+proId+"/"+impId+"/"+returnBy+"/"+InvN,
                    dataType: 'html',
                    success:function (data) {
                        $("#ReturnInvoice").html(data);

                        $(document).ready(function () {
                            $("#returnInvoice").DataTable({});
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