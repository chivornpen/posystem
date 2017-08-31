@extends('layouts.admin')
@section('content')
    <div>
        @include('nav.message')
    </div>
    <div class="container-fluid">
        <br>
        {{--select Brand only Administrator --}}
        <select name="Brand" id="Brand" onchange="Brand()" class="form-control form-group">
            <option value="0">Please select brand name</option>
            @foreach( $brand as $b)
            <option value="{{$b->id}}">{!! $b->brandName !!}</option>
            @endforeach
        </select>
            <div id="admin">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Stock Import
                    </div>
                    <div class="panel panel-body">
                        <div class="container-fluid table-responsive">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table">
                                        @if($subimport->count())
                                            <table class="table table-bordered " style="border-radius: 5px;" id="sDimport">
                                                <thead>
                                                <tr>
                                                    <th class="font" style="text-align: center;">Invoice Numbers</th>
                                                    <th class="font" style="text-align: center;">Import Date</th>
                                                    <th class="font" style="text-align: center;">Supplier</th>
                                                    <th class="font" style="text-align: center;">Export By</th>
                                                    <th class="font" style="text-align: center;">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($subimport as $re)
                                                    <tr>
                                                        <td style="text-align: center;">{!! "CAM-IN-" . sprintf('%06d',$re->purchaseorder_id) !!}</td>
                                                        <td style="text-align: center;">{!! \Carbon\Carbon::parse($re->subimportDate)->format('d-M-Y') !!}</td>
                                                        <td style="text-align: center;">{!! $re->supplier->companyname !!}</td>
                                                        <td style="text-align: center;">{!! $re->user->name !!}</td>
                                                        <td style="text-align: center;">
                                                            <a href="#" onclick="currentViews(this.id)" id="{{$re->id}}" style="margin-right:10px;"><i class="fa fa-outdent" data-toggle="modal" data-target="#current"></i></a>
                                                            <a href="#" onclick="historyviews(this.id)" id="{{$re->purchaseorder_id}}"><i class="fa fa-history" data-toggle="modal" data-target="#histories"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <h4>No Record</h4>
                                        @endif
                                    </div>
                                </div>
                            {{--Modal view import detail--}}
                            <!-- Modal -->
                                <div class="modal fade" id="histories" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                                </div>
                                <div class="modal fade" id="current" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                                </div>
                                {{--End model view import detail--}}
                            </div>
                        </div>
                    </div>
                </div>

                {{--SD stock available--}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Stock Available
                    </div>
                    <div class="panel panel-body">
                        <div class="container-fluid table-responsive">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table">
                                      @if($brand_product->count())
                                        <table class="table table-bordered " style="border-radius: 5px;" id="sdStockAvailable">
                                            <thead>
                                            <tr>
                                                <th class="font" style="text-align: center;">Product Code</th>
                                                <th class="font" style="text-align: center;">Product Name</th>
                                                <th class="font" style="text-align: center;">Quantities</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($brand_product as $re)
                                            <tr>
                                                <td style="text-align: center;">{!! $re->product_code !!}</td>
                                                <td style="text-align: center;">{!! $re->name !!}</td>
                                                <td style="text-align: center;">{!! $re->pivot->qty !!}</td>

                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @else
                                        <h4>No Record</h4>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{url('admin/dashbords')}}" class="btn btn-danger btn-sm">Close</a>
                <div style="margin: 5px; height:5px;"></div>
            </div>
    </div>


@stop
@section('script')
    <script type="text/javascript">
        function Brand() {//View Data by Brand ID
            var brandId= $("#Brand").val();
            if(brandId!=null){
                $.ajax({
                   type: 'get',
                   url: "{{url('sdstock/adminView/')}}"+"/"+brandId,
                   dataType: 'html',
                   success:function (data) {
                       $("#admin").html(data);
                       $(document).ready(function() {
                           $('#sdStockAvailable').DataTable({

                           });
                       });
                       $(document).ready(function() {
                           $('#sDimport').DataTable({

                           });
                       });
                   },
                   error:function (error) {
                       console.log(error);
                   }
                });
            }

        }

        function currentViews(id) {

            $.ajax({
                type:'get',
                url: "{{url('/admin/sdstock/current')}}"+"/"+id,
                dataType:'html',
                success:function (data) {
                    $("#current").html(data);
                },
                error:function (error) {
                    console.log(error);
                }
            });
        }

        function historyviews(id) {
            $.ajax({
                type:'get',
                url: "{{url('/admin/sdstock/histories')}}"+"/"+id,
                dataType:'html',
                success:function (data) {
                    $("#histories").html(data);
                },
                error:function (error) {
                    console.log(error);
                }
            });
        }

        $(document).ready(function() {
            $('#sdStockAvailable').DataTable({

            });
        });
        $(document).ready(function() {
            $('#sDimport').DataTable({

            });
        });
    </script>
@stop
