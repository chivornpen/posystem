@extends('layouts.admin')

@section('content')
        <div class="container-fluid">
            <br>
            <div class="panel panel-default">
                {{--Create Users--}}
                <div class="panel-heading">
                    Requested Views
                </div>
                <div class="panel panel-body">
                    <div class="container-fluid table-responsive">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table">
                                    @if(count($stockoutre))
                                        <table class="table table-bordered " style="border-radius: 5px;" id="requestedPro">
                                            <thead>
                                            <tr>
                                                <th class="font" style="text-align: center;">Requested Number</th>
                                                <th class="font" style="text-align: center;">Export Date</th>
                                                <th class="font" style="text-align: center;">User</th>
                                                <th class="font" style="width:10%; text-align: center;">Detail</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($stockoutre as $sr)
                                                    <tr>
                                                        <td style="text-align: center;">{!! "REQ-".sprintf('%06d',$sr->requestpro_id) !!}</td>
                                                        <td style="text-align: center;">{!! \Carbon\Carbon::parse($sr->outdate)->format('d-M-Y') !!}</td>
                                                        <td style="text-align: center;">{!! \App\User::where('id',$sr->user_id)->value('nameDisplay') !!}</td>
                                                        <td style="text-align: center;">
                                                            <a href="#" onclick="ViewDetail(this.id)" id="{{$sr->id}}" style="margin-right:10px;"><i class="fa fa-outdent" data-toggle="modal" data-target="#myModal"></i></a>

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
                                <a href="{{url('show/requestPro')}}" class="btn btn-info btn-sm">Export Request</a>
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

@endsection
@section('script')
    <script type="text/javascript">
        function ViewDetail(id) {
            $.ajax({
                type: 'get',
                url: "{{url('/show/requested/export/detail/')}}"+"/"+id,
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
            $('#requestedPro').DataTable({
            });
        });
    </script>
@endsection
