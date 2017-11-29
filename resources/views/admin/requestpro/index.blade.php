 @extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-lg-12">                
        <h4 class="page-header"><i class="fa fa-level-up" aria-hidden="true"></i>  All Request Product</h4>
    </div>
</div>
<div>
  @include('nav.message')
</div>
<a href="{{ route('requestpro.create')}}" title="Create new order" class="btn btn-primary btn-sm" > Create Request </a>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
           All Request Product
        </div>
        <div class="panel-body table-responsive">
       <table width="980px" id="example" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>Request Number</th>
                <th>Request Date</th>
                <th>Request By</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reqalls as $reqall)
            <tr>
                <td style="font-size: 11px; font-family: 'Khmer OS System'; text-align: center;">
                    <?php 
                        echo "CAM-REQ-" . sprintf('%06d',$reqall->id);
                    ?>
                </td>
                <td style="font-size: 11px; font-family: 'Khmer OS System'; text-align: center;">
                    {{Carbon\Carbon::parse($reqall->reqDate)->format('d-M-Y')}}
                </td>
                <td style="font-size: 11px; font-family: 'Khmer OS System'; text-align: center;"> 
                    {!! \App\User::where('id',$reqall->reqby)->value('nameDisplay')!!}
                </td>
                <td style="font-size: 11px; font-family: 'Khmer OS System'; text-align: center;"> 
                    {{$reqall->status}}
                </td>
                <td style="text-align: center;">
                    <a href="#" title="Show Detail" onclick="showDetail({{$reqall->id}})" data-toggle="modal" data-target="#myModal"><i class="fa fa-indent"></i></a>
                </td>
            </tr>
            @endforeach
            <script type="text/javascript">

		RemoveSpace();
		function RemoveSpace(){
	
        		var el = document.querySelector('.panel');
        		var doc = el.innerHTML;
        		//alert('Message : ' + doc);
        		el.innerHTML = el.innerHTML.replace(/&nbsp;/g,'');
	
			}

		</script>
        </tbody>
    </table>
        </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

        </div>
    </div>
</div>

@stop
@section('script')
<script type="text/javascript">

$(document).ready(function() {
         $('#example').DataTable({
           "aaSorting": [[ 0, "desc" ]]
        });
    });


            function showDetail(id) {
                $.ajax({
                    type:'get',
                    url: "{{url('/showDetail')}}"+"/"+id,
                    dataType:'html',
                    success:function (data) {
                        $("#myModal").html(data);
                    },
                    error:function (error) {
                        console.log(error);
                    }
                });
            }
</script>
@stop