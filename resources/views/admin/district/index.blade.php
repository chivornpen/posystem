 @extends('layouts.admin')
@section('content')
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #117A65; color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-fw fa-home"></i> New District</h4>
          </div>
            <div class="modal-body">
             <div class="row">
              <div class="col-lg-12">
                {!!Form::open(['action'=>'DistrictController@store','method'=>'POST'])!!}
                  {{csrf_field()}}
                    <div class="row">
                        <div class="col-lg-12">
                           <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                {!!Form::label('name','District Name : ',[])!!}
                                {!!Form::text('name',null,['class'=>'form-control','required'=>'true'])!!}
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                           <div class="form-group {{ $errors->has('province_id') ? ' has-error' : '' }}">
                            {!!Form::label('province_id','Province Name :',[])!!}
                            {!!Form::select('province_id',[null=>'---Please select a province---']+$provinces,null,['class'=>'form-control','required'=>'true'])!!}
                            @if ($errors->has('province_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('province_id') }}</strong>
                                </span>
                            @endif
                          </div>
                        </div>
                    </div>
                <div class="modal-footer" style="background-color: #117A65;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"> Create </button>
               </div>
            {!!Form::close()!!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
    <div class="col-lg-12">                
        <h4 class="page-header"> <i class="fa fa-fw fa-home"></i> District</h4>
    </div>
</div>
<div>
  @include('nav.message')
</div>
<div class="row">
    <div class="col-lg-12">
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"><i class="fa fa-plus" aria-hidden="true"></i> Add New</button>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
           <div class="panel-heading">
           All Districts
        </div>
        <div class="panel-body table-responsive">
        
       <table with="100%" id="example" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Province Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <?php $no=1;?>
        <tbody>
            @foreach($district as $district)
            <tr>
                <td>{{$no++}}</td>
                <td style="font-size: 11px; font-family: 'Khmer OS System';">
                    {{$district->name}}
                </td>
                <td style="font-size: 11px; font-family: 'Khmer OS System';">
                    {{$district->province->name}}</td>
                <td style="text-align: center;">
                    <a href="{{ route('districts.edit',$district->id)}}" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-edit"></i></a>
                    <form action="{{ route('districts.destroy', $district->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Are you sure you want to delete?')) { return true } else {return false };">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button title="Delete" type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                    </form>
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