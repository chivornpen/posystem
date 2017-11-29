@extends('layouts.admin')
@section('content')
<div>
  @include('nav.message')
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading"><i class="fa fa-level-up" aria-hidden="true"></i>  Request Product</div>
        <div class="panel panel-body">
            <div id="mypopup"></div>
            <div class="row">
              <div class="col-lg-12">
                {!!Form::open(['action'=>'RequestproController@store','method'=>'POST'])!!}
                  {{csrf_field()}}
                  <div class="row">
                          <div class="col-lg-3">
                           <div class="form-group {{ $errors->has('reqDate') ? ' has-error' : '' }}">
                            {!!Form::label('reqDate','Request Date ',[])!!}
                            {!!Form::text('reqDate',Carbon\Carbon::parse(\Carbon\Carbon::now())->format('d-M-Y'),['class'=>'form-control','readonly'=>'readonly'])!!}
                            @if ($errors->has('reqDate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('reqDate') }}</strong>
                                </span>
                            @endif
                          </div>
                        </div>
                          <div class="col-lg-3">
                            <div class="form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                              {!!Form::label('user_id','Request By',[])!!}
                              {!!Form::select('user_id',[null=>'---Please select product---']+$requestBy,null,['class'=>'form-control','required'=>'true'])!!}
                                @if ($errors->has('user_id'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('user_id') }}</strong>
                                    </span>
                                  @endif
                            </div>
                          </div>
                          <div class="col-lg-6">
                           <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                            {!!Form::label('status','Description ',[])!!}
                            {!!Form::text('status',null,['class'=>'form-control','required'=>'true'])!!}
                            @if ($errors->has('status'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('status') }}</strong>
                                </span>
                            @endif
                          </div>
                        </div>
                    </div>
                      <div class="panel panel-footer panel-primary">
                        <div class="row">
                          <div class="col-lg-6">
                            <div class="form-group {{ $errors->has('product_id') ? ' has-error' : '' }}">
                              {!!Form::label('product_id','Product Name',[])!!}
                              {!!Form::select('product_id',[null=>'---Please select product---']+$products,null,['class'=>'form-control productId'])!!}
                                @if ($errors->has('product_id'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('product_id') }}</strong>
                                    </span>
                                  @endif
                            </div>
                          </div>
                          <div class="col-lg-3">
                            <div class="form-group {{ $errors->has('product_code') ? ' has-error' : '' }}">
                                {!!Form::label('product_code','Product Code',[])!!}
                                {!!Form::text('product_code',null,['class'=>'form-control proId','readonly'=>'readonly'])!!}
                                  @if ($errors->has('product_code'))
                                    <span class="help-block">
                                      <strong>{{ $errors->first('product_code') }}</strong>
                                    </span>
                                  @endif
                              </div>
                          </div>  
                          <div class="col-lg-3">
                             <div class="form-group {{ $errors->has('qty') ? ' has-error' : '' }}">
                                {!!Form::label('qty','Quantity',[])!!}
                                {!!Form::number('qty',null,['class'=>'form-control qty','readonly'=>'readonly','min'=>'0','autocomplete'=>'off'])!!}
                                  @if ($errors->has('qty'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('qty') }}</strong>
                                      </span>
                                  @endif
                              </div>
                            </div> 
                        </div>
                        <div class="row">
                          <div class="col-lg-12">
                             <a disabled class="btn btn-primary btn-xs add" onclick="addRequestpro()" ><i class="fa fa-cart-plus" aria-hidden="true"></i> Add</a>
                             <a class="btn btn-info btn-xs" onclick="showProduct()" ><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                          </div>
                        </div>
                      </div>
                <div class="row">
                  <div class="col-lg-12 table-responsive" id="MyProList">

                  </div>
                </div>
                <div hidden class="row buttonSave">
                    <div class="col-lg-12">
                      <div class="well-sm">
                        <button type="submit" disabled="true" name="btn_save" value="Save" class="btn btn-success btn-sm" id="btn_hide"> Save</button>
                        <a href="{{url('/admin/discard')}}" disabled="true" class="btn btn-danger btn-sm btn_hide"> Discard </a>
                      </div>
                    </div>
                  </div>
              </div>
              </div>    
                {!!Form::close()!!}
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
  {!!Form::hidden('qty_pro_in_stock',null,['class'=>'qty_pro_in_stock'])!!}
  {!!Form::hidden('tmp_pro_qty',null,['class'=>'tmp_pro_qty'])!!}
@stop
@section('script')
	<script type="text/javascript">
	//---------------------------
	  $('.productId').on('change',function(e){
      var proId= $(this).val();
      $('.qty').removeAttr('readonly','readonly');
      $('.qty').val('');
      $('.qty').focus();
      $('.qty').css('border','1px solid lightblue');
      if(proId==''){
        $('.add').attr('disabled','true');
        $('.qty').attr('readonly','readonly');
         $('.qty').css('border','1px solid lightblue');
        $('.proId').val(null);
        $('.productId').focus();
      }
      if (proId) {
      	getProduct(proId);
      }
      
  });
    function getProduct(id){
	  $.ajax({
	    type: 'GET',
	    url:"{{url('/getProduct')}}"+"/"+id,
	    success:function(response){
	      $('.proId').val(response.pro_code);
	      $('.qty_pro_in_stock').val(response.qty_product);
	      if(response.tmp_pro_qty!=null){
	        $('.tmp_pro_qty').val(response.tmp_pro_qty);
	      }else{
	        $('.tmp_pro_qty').val(0);
	      }
	      $('.price').val(response.price); 
	      },
	      error:function(error){
	        console.log(error);
	      }
	  });
	}
//----------------------------------
//----------------------------------
 $( ".qty" ).keyup(function() {
   var qtys = $('.qty').val();
   var qty_pro_in_stocks = $('.qty_pro_in_stock').val();
   var tmp_pro_qtys = $('.tmp_pro_qty').val();
   var qty = null;
   var quantity = null;
   var qty_pro_in_stock = null;
   var quantities = null;
   quantity = parseInt(tmp_pro_qtys);
   qty = parseInt(qtys);
   qty_pro_in_stock = parseInt(qty_pro_in_stocks);
   quantities = qty + quantity;
   if(quantities >= 0 && quantities <= qty_pro_in_stock){
      $('.add').removeAttr('disabled','true');
      $('.qty').css('border','1px solid lightblue');
   }else if(quantities >= 0 && quantities > qty_pro_in_stock){
      $('.add').attr('disabled','true');
      $('.qty').css('border','1px solid red');
      var tmp_qtys = qty_pro_in_stock - quantity;
      alert("Stock available only: "+tmp_qtys+" items!");
      $('.qty').val(null);
   }else{
    $('.amount').val(0);
      $('.add').attr('disabled','true');
      $('.qty').css('border','1px solid red');
    }
});
 //-----------------------------------
  //-------------------------
  function showProduct(){
    $.ajax({
      url:"{{url('/showProduct')}}",
      type: 'get',
      dataType: 'html',
      success:function(data){
        $('#MyProList').html(data);
      },
      error:function(e){
        console.log(e);
      },
    });
  }
 
  //------------------------
    //-------------------
  function addRequestpro(){
	  var proid =$(".productId").val();
	  var qty = $(".qty").val();
	  if (proid && qty) {
	  	$.ajax({
		    url:"{{url('/addRequestpro')}}"+"/"+proid+"/"+qty,
		    type:'get',
		    dataType: 'json',
		    success:function(data){
		      $('.add').attr('disabled','true');
		      $(".productId").val('');
		      $('.proId').val(null);
		      $('.qty').val(null)
		      $('.qty').attr('readonly','readonly');
		      $('#btn_hide').removeAttr('disabled','true');
		    $('.btn_hide').removeAttr('disabled','true');
		    $('.buttonSave').fadeIn('slow');
		      showProduct();
		    },
		    error:function(error){
		      console.log(error)
		    },
		  });
	  }
	}
	function removeRequestpro(id){
	  $.ajax({
	    type: 'get',
      	dataType: 'html',
	    url:"{{url('/removeRequestpro')}}"+"/"+id,
	    success:function(data){
	    	var count = $('table tr').length;
		      if(count==2){
		      $('#btn_hide').attr('disabled','true');
		      $('.btn_hide').attr('disabled','true');
          $('.buttonSave').fadeOut('slow');
		     }
	    	showProduct();
	    },
	    error:function(error){
	      console.log(error)
	    },
	  });
	}
//------------------------
	</script>
@stop