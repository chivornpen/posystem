@extends('layouts.admin')
@section('content')
    <div>
        @include('nav.message')
    </div>
    <div class="container-fluid">
        <br>
        <div class="panel panel-default">
            {{--Create Users--}}
            <div class="panel-heading">
                Stock Out
            </div>
            <div class="panel panel-body">
                <div class="container-fluid table-responsive">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table">
                                @if($stockout->count())
                                    <table class="table table-bordered " style="border-radius: 5px;" id="import">
                                        <thead>
                                        <tr>
                                            <th class="font" style="text-align: center;">Invoice Numbers</th>
                                            <th class="font" style="text-align: center;">Stock Out Date</th>
                                            <th class="font" style="text-align: center;">Customer Name</th>
                                            <th class="font" style="width:10%; text-align: center;">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($stockout as $re)
                                            <tr>
                                                <td style="text-align: center;">{{"CAM-IN-" . sprintf('%06d',$re->purchaseordersd_id)}}</td>
                                                <td style="text-align: center;">{{Carbon\Carbon::parse($re->stockoutDate)->format('d-M-Y')}}</td>
                                                <td style="text-align: center;">{{$re->purchaseordersd->customer->name}}</td>
                                                <td style="text-align: center;">
                                                    <a href="#" onclick="Views({{$re->purchaseordersd_id}})" style="margin-right:10px;"><i class="fa fa-outdent" data-toggle="modal" data-target="#myModal"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <h4>No Record</h4>
                                @endif
                            </div>
                            <a href="{{url('admin/dashbords')}}" class="btn btn-danger btn-sm">Close</a>
                        </div>
                    {{--Modal view import detail--}}
                    <!-- Modal -->
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                        </div>
                        {{--End model view import detail--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')
    <script type="text/javascript">
        function Views(id) {
            $.ajax({
               type: 'get',
                url: "{{url('viewDetailStockoutSd')}}"+"/"+id,
                dataType: 'html',
                success:function (data) {
                    $("#myModal").html(data);
                },
                error:function (error) {
                    console.log(error);
                }
            });
        }
        $(document).ready(function() {
            $('#import').DataTable({

            });
        });
    </script>
@stop
