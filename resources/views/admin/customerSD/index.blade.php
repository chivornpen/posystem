@extends('layouts.admin')
@section('content')
@include('include.popupCusSD')
<div class="row">
    <div class="col-lg-12">                
        <h4 class="page-header"><i class="fa fa-user" aria-hidden="true"></i> Customer</h4>
    </div>
</div>
<div>
  @include('nav.message')
</div>
<div class="row">
    <div class="col-lg-12">
        <button title="Add New"  type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"><i class="fa fa-plus" aria-hidden="true"></i> Add New</button>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
        <div class="panel-heading">
           All Customers
        </div>
        <div class="panel-body table-responsive">
       <table with="100%" id="example" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Channel</th>
                <th>Location</th>
                <th>Action</th>
            </tr>
        </thead>
        <?php $no=1;?>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td style="font-size: 11px; font-family: 'Khmer OS System'; text-align: center;">{{$no++}}</td>
                <td style="font-size: 11px; font-family: 'Khmer OS System';"> 
                    {{$customer->name}}
                </td>
                <td style="font-size: 11px; font-family: 'Khmer OS System';">
                    {{$customer->contactNo}}
                </td>
                <td style="font-size: 11px; font-family: 'Khmer OS System'; text-align: center;">  
                    <?php 
                        if($customer->channel_id!=null){
                            echo $customer->channel->name;
                        }else{
                            echo "";
                        }
                    ?>
                </td>
                <td style="font-size: 11px; font-family: 'Khmer OS System'; text-align: center;">  
                    {{$customer->location}}
                </td>
                <td style="text-align: center;">
                    <a href="{{ route('customers.edit',$customer->id)}}" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-edit"></i></a>
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
    </div>
</div>
@stop
@section('script')
<script type="text/javascript">
      $(document).ready(function() {
         $('#example').DataTable({
            responsive: true
        });
    });
</script>
@stop