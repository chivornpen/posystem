@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">
               View Product Return
            </div>
            <div class="panel-body table-responsive">
                <div style="padding: 0 1.5% 0 0 ">
                    @if($returnpro->count())
                            <table class="table table-bordered" id="viewProductReturn">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">No</th>
                                        <th style="text-align: center;">Invoice Return</th>
                                        <th style="text-align: center;">New Invoice</th>
                                        <th style="text-align: center;">Status</th>
                                        <th style="text-align: center;">Date Created</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <?php $i=1; ?>
                                <tbody>
                                @foreach($returnpro as $re)
                                    <tr>
                                        <td style="text-align: center;">{!! $i++ !!}</td>
                                        <td style="text-align: center;">
                                            {!!"CAM-IN-".sprintf("%06d",$re->stockoutsd->purchaseordersd_id)!!}
                                        </td>
                                        <td style="text-align: center;">{!! $re->purchaseordersd_id ==0 ? "No created Invoice" : "CAM-IN-".sprintf("%06d",$re->purchaseordersd_id)   !!}</td>
                                        <td style="text-align: center;">{!! strtolower($re->status)=="s" ? "Some products" : "Return All" !!}</td>
                                        <td style="text-align: center;">{!! \Carbon\Carbon::parse($re->created_at)->format('d-M-Y') !!}</td>
                                        <td style="text-align: center;">
                                            <a href="#" title="View Product Return" onclick="ViewProductReturn('{{$re->id}}','{{$re->status}}','{{$re->stockoutsd_id}}')" data-toggle="modal" data-target="#myModal"><i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                        <h4>No Found results</h4>
                    @endif
                </div>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                    </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
        <script type="text/javascript">
            function ViewProductReturn(reId,status,stockoutId) {
                $.ajax({
                    type: 'get',
                    url: "{{url('viewProductReturn')}}"+"/"+reId+"/"+status+"/"+stockoutId,
                    dataType: 'html',
                    success:function (data) {
                        $("#myModal").html(data);
                    },
                    error:function (error) {

                        console.log(error);
                    }
                });
            }


            $(document).ready(function () {
                $("#viewProductReturn").DataTable({});
            });
        </script>
@endsection