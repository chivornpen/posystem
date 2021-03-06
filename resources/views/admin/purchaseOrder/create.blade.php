@extends('layouts.admin')
@section('content')
<div>
  @include('nav.message')
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading"><i class="fa fa-shopping-basket" aria-hidden="true"></i> Create New Purchase Order</div>
        <div class="panel panel-body">
            <div id="mypopup"></div>
            <div class="row">
              <div class="col-lg-12">
                {!!Form::open(['action'=>'PurchaseOrderController@store','method'=>'POST','id'=>'myFormPO'])!!}
                  {{csrf_field()}}
                      <div class="row">
                          <div class="col-lg-3">
                           <div class="form-group {{ $errors->has('poDate') ? ' has-error' : '' }}">
                            {!!Form::label('poDate','Purchase Order Date :',[])!!}
                            {!!Form::date('poDate',null,['class'=>'form-control poDate','required'=>'true'])!!}
                            @if ($errors->has('poDate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('poDate') }}</strong>
                                </span>
                            @endif
                          </div>
                        </div>
                          <div class="col-lg-8 hideChange">
                           <div class="form-group {{ $errors->has('customer_id') ? ' has-error' : '' }}">
                            {!!Form::label('customer_id','Customer Name :',[])!!}
                            <div class="input-group">
                            <select id="customername" class="form-control customerid" name="customer_id">
                              <option value="0">Please select customer name</option>
                                @foreach($customers as $cus)
                                  <option value="{{$cus->id}}">{{$cus->name}}</option>
                                @endforeach
                                </select>
                            <span class="input-group-btn">
                              <button title="Add New Customer"  type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal" onclick="getPopupCus()"><i class="fa fa-user-plus" aria-hidden="true"></i> Add</button>
                            </span>
                          </div>
                            @if ($errors->has('customer_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('customer_id') }}</strong>
                                </span>
                            @endif
                          </div>
                        </div>
                    </div>
                    <div hidden class="mydiv">
                      <div class="row">
                      <div class="col-lg-2">
                           <div class="form-group {{ $errors->has('customerid') ? ' has-error' : '' }}">
                            {!!Form::label('customerid','Customer ID :',[])!!}
                            {!!Form::text('customerid',null,['class'=>'form-control cusId','readonly'=>'readonly','disabled'=>'true'])!!}
                            @if ($errors->has('customerid'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('customerid') }}</strong>
                                </span>
                            @endif
                          </div>
                        </div>
                        <div class="col-lg-3">
                             <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                              {!!Form::label('name','Customer Name :',[])!!}
                              {!!Form::text('name',null,['class'=>'form-control cusname','readonly'=>'readonly','disabled'=>'true'])!!}
                              @if ($errors->has('name'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('name') }}</strong>
                                  </span>
                              @endif
                            </div>
                          </div>
                          <div class="col-lg-2">
                             <div class="form-group {{ $errors->has('homeNo') ? ' has-error' : '' }}">
                              {!!Form::label('homeNo','Home No :',[])!!}
                              {!!Form::text('homeNo',null,['class'=>'form-control cusHomeNo','readonly'=>'readonly','disabled'=>'true'])!!}
                              @if ($errors->has('homeNo'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('homeNo') }}</strong>
                                  </span>
                              @endif
                            </div>
                          </div>
                          <div class="col-lg-2">
                             <div class="form-group {{ $errors->has('streetNo') ? ' has-error' : '' }}">
                              {!!Form::label('streetNo','Street No :',[])!!}
                              {!!Form::text('streetNo',null,['class'=>'form-control cusStreetNo','readonly'=>'readonly','disabled'=>'true'])!!}
                              @if ($errors->has('streetNo'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('streetNo') }}</strong>
                                  </span>
                              @endif
                            </div>
                          </div>
                          <div class="col-lg-3">
                             <div class="form-group {{ $errors->has('channel_id') ? ' has-error' : '' }}">
                              {!!Form::label('channel_id','Channel :',[])!!}
                              {!!Form::text('channel_id',null,['class'=>'form-control channel','readonly'=>'readonly','disabled'=>'true'])!!}
                              @if ($errors->has('channel_id'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('channel_id') }}</strong>
                                  </span>
                              @endif
                            </div>
                          </div>
                        </div>
                      <div class="panel panel-footer panel-primary">
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="form-group {{ $errors->has('product_id') ? ' has-error' : '' }}">
                              {!!Form::label('product_id','Product Name',[])!!}
                              {!!Form::select('product_id',[null=>'---Please select product---']+$product_name,null,['class'=>'form-control productId'])!!}
                                @if ($errors->has('product_id'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('product_id') }}</strong>
                                    </span>
                                  @endif
                            </div>
                          </div>
                          <div class="col-lg-2">
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
                          <div class="col-lg-2">
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
                          <div class="col-lg-2">
                            <div class="form-group {{ $errors->has('unitPrice') ? ' has-error' : '' }}">
                                {!!Form::label('unitPrice','Unit Price',[])!!}
                                {!!Form::text('unitPrice',0,['class'=>'form-control price','readonly'=>'readonly'])!!}
                                  @if ($errors->has('unitPrice'))
                                    <span class="help-block">
                                      <strong>{{ $errors->first('unitPrice') }}</strong>
                                    </span>
                                  @endif
                            </div>
                          </div> 
                          <div class="col-lg-2">
                            <div class="form-group {{ $errors->has('amount') ? ' has-error' : '' }}">
                                {!!Form::label('amount','Amount',[])!!}
                                {!!Form::text('amount',0,['class'=>'form-control amount','readonly'=>'readonly'])!!}
                                  @if ($errors->has('amount'))
                                    <span class="help-block">
                                      <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                  @endif
                            </div>
                          </div> 
                        </div>
                        <div class="row">
                          <div class="col-lg-12">
                             <a disabled class="btn btn-primary btn-xs add" onclick="addOrderCus()" ><i class="fa fa-cart-plus" aria-hidden="true"></i> Add</a>
                             <a class="btn btn-info btn-xs" onclick="showProductCus()" ><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                             <a href="{{url('/admin/cancel')}}" class="btn btn-warning btn-xs pull-right">Cancel</a>
                          </div>
                        </div>
                      </div>
                      
                      {{----------------------------------------------}}
                    <div class="row">
                      <div hidden class="col-lg-3 columnhide">
                           <div class="form-group {{ $errors->has('dueDate') ? ' has-error' : '' }}">
                            {!!Form::label('dueDate','Due Date :',[])!!}
                            {!!Form::date('dueDate',null,['class'=>'form-control','required'=>'true'])!!}
                            @if ($errors->has('dueDate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('dueDate') }}</strong>
                                </span>
                            @endif
                          </div>
                        </div>
                        <div hidden class="col-lg-7 columnhide">
                         
                        </div>
                        <div hidden class="col-lg-2 showCheckbox">
                          @if(Auth::user()->position->name !='Sale')
                          <div class="checkbox checkbox-danger">
                              <input id="isDelivery" type="checkbox" value="1" name="isDelivery">
                              <label for="isDelivery" style="color:red;font-weight: bold; font-family: 'Khmer OS Siemreap',Serif;">
                                 Is Delivery
                              </label>
                          </div>
                          @endif
                           <div class="checkbox checkbox-success">
                            <input id="cod" type="checkbox" value="1" name="cod" class="cod">
                              <label for="cod" style="color:green; font-weight: bold; font-family: 'Khmer OS Siemreap',Serif;">
                                 Is COD
                              </label>
                          </div>
                        </div>
                      </div>
                      {{----------------------------------------------}}
                <div class="row">
                  <div class="col-lg-12" id="MyProList">
                   <!--  table -->
                  </div>
                </div>
              <div id="showto" hidden>
                <div class="row">
                  <dir class="col-lg-8">
                    
                  </dir>
                  <div class="col-md-2">
                    {!!Form::label('total','Total :',[])!!}
                  </div>
                  <div class="col-lg-2">
                    {!!Form::number('total',0,['class'=>'form-control totalcus','readonly'=>'readonly'])!!}
                  </div>
                </div>
                <div class="row" id="vat" hidden>
                  <dir class="col-lg-8">
                    
                  </dir>
                  <div class="col-lg-2">
                    {!!Form::label('vat','VAT :',[])!!}
                  </div>
                  <div class="col-lg-2">
                    {!!Form::number('vat',0,['class'=>'form-control vat','readonly'=>'readonly'])!!}
                  </div>
                </div>
                <div class="row" id="discount" hidden>
                  <dir class="col-lg-8">
                    
                  </dir>
                  <div class="col-lg-2">
                    {!!Form::label('discount','Discount :',[])!!}
                  </div>
                  <div class="col-lg-2">
                    {!!Form::number('discount',null,['class'=>'form-control discountcus','min'=>'0','max'=>'100'])!!}
                  </div>
                </div>
                <div class="row" id="showcod" hidden>
                  <dir class="col-lg-8">
                    
                  </dir>
                  <div class="col-lg-2">
                    {!!Form::label('cod','COD :',[])!!}
                  </div>
                  <div class="col-lg-2">
                   {!!Form::text('cod',null,['class'=>'form-control showcod','readonly'=>'readonly'])!!}
                  </div>
                </div>
                <div class="row">
                  <dir class="col-lg-8">
                    
                  </dir>
                  <div class="col-lg-2">
                    {!!Form::label('grandTotal','Grand Total :',[])!!}
                  </div>
                  <div class="col-lg-2">
                      {!!Form::text('grandTotal',0,['class'=>'form-control grandTotalcus','readonly'=>'readonly'])!!}
                  </div>
                </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="well-sm">
                        <button type="submit" disabled="true" name="btn_save" value="Save" class="btn btn-success btn-sm" id="btn_hide"> Save</button>
                        <a href="{{url('/admin/cancel')}}" disabled="true" class="btn btn-danger btn-sm btn_hide"> Discard </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
                
                    
                        {!!Form::hidden('codcus',$codcus,['id'=>'codcus'])!!}
                        <!-- {!!Form::hidden('discus1',$discus1,['id'=>'discus1'])!!}
                        {!!Form::hidden('discus2',$discus2,['id'=>'discus2'])!!} -->
                        {!!Form::hidden('setdiscus1',$setdiscus1,['id'=>'setdiscus1'])!!}
                       <!--  {!!Form::hidden('setdiscus2',$setdiscus2,['id'=>'setdiscus2'])!!} -->
                       {!!Form::hidden('vat',$vat,['id'=>'vat'])!!}
                        {!!Form::hidden('discount',null,['id'=>'dis'])!!}
                        {!!Form::hidden('grandTotal',null,['id'=>'gtotal'])!!}
                        {!!Form::hidden('qty_pro_in_stock',null,['class'=>'qty_pro_in_stock'])!!}
                        {!!Form::hidden('tmp_pro_qty',null,['class'=>'tmp_pro_qty'])!!}
                    
                {!!Form::close()!!}
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
@stop
@section('script')
<script type="text/javascript">
$(document).ready(function() {
                $('.customerid').select2();
                //showProduct();
    });

  $('.customerid').on('change',function(e){
      var cusId= $(this).val();
      var id = 0;
      var num = cusId;
      var count = (num.toString().length);
      if(count==1){
        id = 'CAM-CUS-00000' + num;
      }else if(count==2){
        id = 'CAM-CUS-0000' + num;
      }else if(count==3){
        id = 'CAM-CUS-000' + num;
      }else if(count==4){
        id = 'CAM-CUS-00' + num;
      }else if(count==5){
        id = 'CAM-CUS-0' + num;
      }else if(count==6){
        id = 'CAM-CUS-' + num;
      }else{
        
      }
      $('.cusId').val(id);
      $('.hideChange').fadeOut("slow");
      $('.showBack').fadeIn("slow");
      $('.mydiv').fadeIn("slow");
      if(cusId==''){
         $('.mydiv').fadeOut("slow");
      };
      getEmailCustomer(cusId);
  });
  $('.productId').on('change',function(e){
      var proId= $(this).val();
      $('.qty').removeAttr('readonly','readonly');
      $('.qty').val('');
      $('.qty').focus();
      $('.qty').css('border','1px solid lightblue');
      $('.amount').val(0);
      if(proId==''){
        $('.add').attr('disabled','true');
        $('.qty').attr('readonly','readonly');
         $('.qty').css('border','1px solid lightblue');
        $('.proId').val(null);
        $('.price').val(0);
        $('.amount').val(0);
        $('.productId').focus();
      }
      getProduct(proId);
  });
  //---------------------------
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
      var price = $('.price').val();
      var total = qty * price;
      var amount = total.toFixed(2);
      $('.amount').val(amount);
   if(quantities >= 0 && quantities <= qty_pro_in_stock){
      $('.add').removeAttr('disabled','true');
      $('.qty').css('border','1px solid lightblue');
   }else if(quantities >= 0 && quantities > qty_pro_in_stock){
      $('.add').attr('disabled','true');
      $('.qty').css('border','1px solid red');
      var tmp_qtys = qty_pro_in_stock - quantity;
      alert("Stock available only: "+tmp_qtys+" items!");
      $('.qty').val(null)
      $(".amount").val(0);
   }else{
    $('.amount').val(0);
      $('.add').attr('disabled','true');
      $('.qty').css('border','1px solid red');
    }
});
 //-----------------------------------
//------------------------

  var total = 0;
  $(document).on('change','tr #total',function(e){

  });
  $('.discount').on('change',function(e){
        var dis = $(this).val();
        var total = $('.total').val();
        var grandTotal = total - (total * dis)/100;
        $('.grandTotal').val(grandTotal);
  });
  //------------------------------------------------
  function getPopupCus(){
      $.ajax({
      url:"{{url('/getPopup')}}",
      type:'get',
      dataType:'html',
      success:function(data){
        $("#mypopup").append(data);
      },
      error:function(er){
        console.log(er);
      },

    });
    
    $('.province_id').on('change',function(e){
     var f =document.getElementById("dis_id");
     var province = $(this).val();
     if(province ==''){
     	$('.district_id').val('');
     }
     var url = "{{url('/getProvince')}}"+"/";
     console.log(province);
     getValueCombo(province,url,f);
  });
   $('.district_id').on('change',function(e){
     var f =document.getElementById("com_id");
     var district = $(this).val();
     if(district ==''){
     	$('.commune_id').val('');
     }
     var url = "{{url('/getDistrict')}}"+"/";
     console.log(district);
     getValueCombo(district,url,f);
  });
   $('.commune_id').on('change',function(e){
     var f =document.getElementById("vil_id");
     var commune = $(this).val();
     if(commune ==''){
     	$('.village_id').val('');
     }
     var url =  "{{url('/getCommune')}}"+"/";
     console.log(commune);
     getValueCombo(commune,url,f);
  });

  };
function getValueCombo(id,ul,f)
{
   $.ajax({
    method: 'GET',
      url: ul+id,
      success:function(response){
        console.log(response)
        if(Array.isArray(response)){

            $(f).empty();
            var serialnumber="<option value=''>---Please select option---</option>";
            $(f).append(serialnumber);
            response.map(function(item){
              console.log(item.name);
              serialnumber="<option value=" + item.id + ">" + item.name + "</option>";;
              $(f).append(serialnumber);
            });
        }
      },
      error:function(error){
        console.log(error);
      }
   })
};
//-----------------------
function getEmailCustomer(id){
    $.ajax({
      method: 'GET',
      url:"{{url('/getCustomer')}}"+"/"+id,
      success:function(response){
        $('.cusHomeNo').val(response[0].homeNo);
        $('.cusStreetNo').val(response[0].streetNo);
        $('.cusname').val(response[0].name);
        $('.channel').val(response[1].description);
      },
      error:function(error){
        console.log(error);
      }
    });
  }
   //-------------------
  function addOrderCus(){
  var proid =$(".productId").val();
  var qty = $(".qty").val();
  var price =$(".price").val();
  var amount = $(".amount").val();
  //console.log([scores,studentid]);
  if(qty){
      $.ajax({
        url:"{{url('/addOrderCus')}}"+"/"+proid+"/"+qty+"/"+price+"/"+amount,
        type:'get',
        dataType: 'json',
        success:function(data){
          $('.add').attr('disabled','true');
          $('#showto').fadeIn();
          $(".productId").val('');
          $('.proId').val(null);
          $('.qty').val(null)
          $('.qty').attr('readonly','readonly');
          $(".price").val(0);
          $(".amount").val(0);
          $(".total").val(1000);
          $('#btn_hide').removeAttr('disabled','true');
          $('.btn_hide').removeAttr('disabled','true');
          $('.columnhide').fadeIn('slow');
          $('.showCheckbox').fadeIn('slow');
          showProductCus();
          getTotalCus();
        },
        error:function(error){
          console.log(error)
        },
      });
  }
}
 //-------------------------
  function showProductCus(){
    $.ajax({
      url:"{{url('/showProductCus')}}",
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
  function getTotalCus() {
$.ajax({
  url:"{{url('/getTotalCus')}}",
  type:'get',
  success:function(data){
      
  var grandTotalcus =0;
  var totalcus = 0;
  totalcus = data;
  total = data.toFixed(2);
  $('.totalcus').val(total);
  $('#MyProList').fadeIn('slow');
  var setdiscus1 = $('#setdiscus1').val();
  if(totalcus >= setdiscus1){
          $('#discount').fadeIn('slow');
          $('.discountcus').val(0);
          $('.grandTotalcus').val(total);
          $( ".discountcus" ).keyup(function() {
            $('.cod').filter(':checkbox').prop('checked',false);
            $('.showcod').val(0);
            $('#showcod').fadeOut('slow');
            var dis = $(this).val();
            if(dis<0){
                $('.discountcus').css('border','1px solid red');
                $('#btn_hide').attr('disabled','true');
            }else if(dis>100){
                $('.discountcus').css('border','1px solid red');
                $('#btn_hide').attr('disabled','true');
            }else{
                $('#btn_hide').removeAttr('disabled','true');
                 $('.discountcus').css('border','1px solid lightblue');
            }
            graTotalcus = totalcus - (totalcus * dis)/100;
            grandTotalcus = graTotalcus.toFixed(2);
          $('.grandTotalcus').val(grandTotalcus);
          });          
  }else{
      $('#discount').fadeOut(1000);
      $('.grandTotalcus').val(total);
  }
  $("#myFormPO").submit(function(){
    var dis = $('.discountcus').val();
    var grandTotal = $(".grandTotalcus").val();
    $('#dis').val(dis);
    console.log(grandTotal);
    $('#gtotal').val(grandTotal);
  });
  },
  //end sucess
  error:function(e){
    console.log(e);
  },
});

  }
 //-------------------------------
function removeOrderCus(id){
  $.ajax({
    method: 'GET',
    url:"{{url('/removeOrderCus')}}"+"/"+id,
    success:function(data){
      $(".productId").val('');
      $('.proId').val(null);
      $('.qty').val(null)
      $('.qty').attr('readonly','readonly');
      $(".price").val(0);
      $(".amount").val(0);
      showProductCus();
      getTotalCus(); 
      $(".table tr#"+data).remove();
      $('.cod').attr('checked',false);
      $('.discountcus').val(0);
      var count = $('table tr').length;
      if(count==1){
      $('#btn_hide').attr('disabled','true');
      $('.btn_hide').attr('disabled','true');
      $('.columnhide').fadeOut('slow');
      $('.showCheckbox').fadeOut('slow');
      $('#showto').fadeOut('slow');
     }
    },
    error:function(error){
      console.log(error)
    },
  });
}
//------------------------
$(document).ready(function(){
$('.cod').on('change',function(){
  if (this.checked) {
    var totalcus =0;
    var grandTotalcus =0;
    var totalcus = $('.totalcus').val();
    var codcus = $('#codcus').val();
    $('.showcod').val(codcus);
    $('#showcod').fadeIn('slow');
    var discountcus = $('.discountcus').val();
      if(discountcus!=''){
        var totaldis = totalcus - totalcus * discountcus/100;
        var grandTotal = totaldis - totaldis * codcus/100;
            grandTotalcus = grandTotal.toFixed(2);
         $('.grandTotalcus').val(grandTotalcus);
      }else{
        var codcus = $('#codcus').val();
        var totalcus = $('.totalcus').val();
        var grandTotal = totalcus - (totalcus * codcus)/100;
            grandTotalcus = grandTotal.toFixed(2);
        $('.grandTotalcus').val(grandTotalcus);
      }
  } else {
      $('.showcod').val(0);
      $('#showcod').fadeOut('slow');
      var dis = $('.discountcus').val();
      var totalcus = $('.totalcus').val();
      var grandTotal = totalcus - (totalcus * dis)/100;
          grandTotalcus = grandTotal.toFixed(2);
      $('.grandTotalcus').val(grandTotalcus);
      
    }
 });
});
   
</script>

@stop