@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">
                View Product Exchange
            </div>
            @if($exchange)
                <div class="panel-body table-responsive">
                    <table class="table table-bordered" id="viewExchange">
                        <thead>
                            <tr>
                                <th style="text-align: center;">No</th>
                                <th style="text-align: center;">Old Invoice Number</th>
                                <th style="text-align: center;">Invoice Exchange</th>
                                <th style="text-align: center;">Date Create</th>
                                <th style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i=1;?>
                            @foreach($exchange as $ex)
                                <tr>
                                    <td style="text-align: center;">{!! $i++ !!}</td>
                                    <td style="text-align: center;">{!! "CAM-IN-".sprintf('%06d',$ex->stockoutsd->purchaseordersd_id) !!}</td>
                                    <td style="text-align: center;">{!! $ex->purchaseorder_id ? "CAM-IN-".sprintf('%06d',$ex->purchaseorder_id) : "Not yet" !!}</td>
                                    <td style="text-align: center;">{!! \Carbon\Carbon::parse($ex->created_at)->format('d-M-Y') !!}</td>
                                    <td style="text-align: center;">
                                        <a href="#" title="Views" onclick="viewDetail('{{$ex->id}}')" data-toggle="modal" data-target="#viewExchangeDetail"><i class="fa fa-outdent"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            @else
                <h4>No record</h4>
            @endif
        </div>
        <div class="modal fade" id="viewExchangeDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function viewDetail(id) {
           $.ajax({
               type: 'get',
               url: "{{url('/viewDetailExchangesd')}}"+"/"+id,
               dataType: 'html',
               success:function (data) {
                   $('#viewExchangeDetail').html(data);
               },
               error:function (error) {
                   console.log(error);
               }
           });
        }



        $(document).ready(function() {
            $('#viewExchange').DataTable({});
        });
    </script>

@endsection