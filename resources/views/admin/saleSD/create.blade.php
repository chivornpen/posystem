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
                {!!Form::open(['action'=>'SaleSDController@store','method'=>'POST','id'=>'myFormPO'])!!}
                  {{csrf_field()}}
                      <div class="row">
                          <div class="col-lg-3">
                           <div class="form-group {{ $errors->has('poDate') ? ' has-error' : '' }}">
                            {!!Form::label('poDate','Purchase Order Date :',[])!!}
                            {!!Form::text('poDate',Carbon\Carbon::parse(\Carbon\Carbon::now())->format('d-M-Y'),['class'=>'form-control poDate','readonly'=>'readonly'])!!}
                            @if ($errors->has('poDate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('poDate') }}</strong>
                                </span>
                            @endif
                          </div>
                        </div>
                          <div class="col-lg-8 hideChange">
                           <div class="form-group {{ $errors->has('customer_id') ? ' has-error' : '' }}">
                           {!!Form::label('cus','Customer Name :',[])!!}
                           <div class="input-group">
                             <select id="cus" class="form-control customer_id" name="customer_id">
                              <option value="0">Please select customer name</option>
                                @foreach($customers as $cus)
                                  <option value="{{$cus->id}}">{{$cus->name}}</option>
                                @endforeach
                                </select>
                                <span class="input-group-btn">
                                  <button title="Add New Customer"  type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#exampleModal" onclick="getPopupCusSD()"><i class="fa fa-user-plus" aria-hidden="true"></i> Add</button>
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
                      <div class="col-lg-4">
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
                        <div class="col-lg-4">
                             <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                              {!!Form::label('name','Customer Name :',[])!!}
                              {!!Form::text('name',null,['class'=>'form-control cusname','disabled'=>'true'])!!}
                              @if ($errors->has('name'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('name') }}</strong>
                                  </span>
                              @endif
                            </div>
                          </div>
                          <div class="col-lg-4">
                             <div class="form-group {{ $errors->has('contactNo') ? ' has-error' : '' }}">
                              {!!Form::label('contactNo','Customer ContactNo :',[])!!}
                              {!!Form::text('contactNo',null,['class'=>'form-control contactNo','disabled'=>'true'])!!}
                              @if ($errors->has('contactNo'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('contactNo') }}</strong>
                                  </span>
                              @endif
                            </div>
                          </div>
                        </div>
                      <div class="panel panel-footer panel-primary">
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="form-group {{ $errors->has('product_id') ? ' has-error' : '' }}">
                            {!!Form::label('pro','Product Name :',[])!!}
                             <select id="pro" class="form-control productId" name="customer_id">
                              <option value="{{null}}">Please select product name</option>
                                @foreach($products as $pro)
                                  <option value="{{$pro->id}}">{{$pro->name}}</option>
                                @endforeach
                              </select>
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
                                {!!Form::number('unitPrice',0,['class'=>'form-control price','readonly'=>'readonly','min'=>'0','autocomplete'=>'off'])!!}
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
                             <a disabled class="btn btn-success btn-sm add" onclick="addOrderSDSale()" ><i class="fa fa-cart-plus" aria-hidden="true"></i> Add</a>
                             <button type="submit" name="btn_back" value="Back" class="btn btn-default btn-sm pull-right"> Back </button>
                          </div>
                        </div>
                      </div>
                      
                      {{----------------------------------------------}}
                    <div class="row">
                      <div hidden class="col-lg-3 columnhide">
                           <div class="form-group {{ $errors->has('dueDate') ? ' has-error' : '' }}">
                            {!!Form::label('dueDate','Due Date :',[])!!}
                            {!!Form::date('dueDate',null,['class'=>'form-control'])!!}
                            @if ($errors->has('dueDate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('dueDate') }}</strong>
                                </span>
                            @endif
                          </div>
                        </div>
                        <div hidden class="col-lg-9 columnhide">
                         
                        </div>
                      </div>
                      {{----------------------------------------------}}
                <div class="row">
                  <div class="col-lg-12">
                    <div class="panel panel-default table-responsive" id="MyProList">
                   <!--  table -->
                    </div>
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
                <div class="row" id="discount" hidden>
                  <dir class="col-lg-8">
                    
                  </dir>
                  <div class="col-lg-2">
                    {!!Form::label('discount','Discount % :',[])!!}
                  </div>
                  <div class="col-lg-2">
                    {!!Form::number('discount',null,['class'=>'form-control discountcus','autocomplete'=>'off','min'=>'0','max'=>'100'])!!}
                  </div>
                </div>
                <div class="row" id="cod" hidden>
                  <dir class="col-lg-8">
                    
                  </dir>
                  <div class="col-lg-2">
                    {!!Form::label('cod','COD % :',[])!!}
                  </div>
                  <div class="col-lg-2">
                    {!!Form::number('cod',0,['class'=>'form-control cod','autocomplete'=>'off','min'=>'0','max'=>'100'])!!}
                  </div>
                </div>
                <div class="row" id="vat" hidden>
                  <dir class="col-lg-8">
                    
                  </dir>
                  <div class="col-lg-2">
                    {!!Form::label('vat','VAT% :',[])!!}
                  </div>
                  <div class="col-lg-2">
                    {!!Form::number('vat',0,['class'=>'form-control vat','readonly'=>'readonly'])!!}
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
                        <button type="submit" disabled="true" name="btn_save" value="Save" class="btn btn-primary btn-sm" id="btn_hide"> Save</button>
                        <button disabled="true" type="submit" name="btn_cancel" value="Cancel" class="btn btn-danger btn-sm btn_hide"> Discard </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
                    {!!Form::hidden('cus',null,['class'=>'cus'])!!}
                    {!!Form::hidden('qtySubStock',null,['class'=>'qtySubStock'])!!}
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
                $('.customer_id').select2();
                //showProduct();
    });

  $('.customer_id').on('change',function(e){
      var cusId= $(this).val();
      $('.cus').val(cusId);
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
      $('.price').removeAttr('readonly','readonly');
      $('.qty').val('');
      $('.price').val(0);
      $('.price').css('border','1px solid lightblue');
      $('.qty').focus();
      $('.qty').css('border','1px solid lightblue');
      $('.amount').val(0);
      if(proId==''){
        $('.productId').focus();
        $('.add').attr('disabled','true');
        $('.qty').attr('readonly','readonly');
        $('.price').attr('readonly','readonly');
        $('.qty').css('border','1px solid lightblue');
        $('.price').css('border','1px solid lightblue');
        $('.proId').val(null);
        $('.price').val(0);
        $('.amount').val(0);
      }
      getProductSubStock(proId);
  });
  //---------------------------
    function getProductSubStock(id){
  $.ajax({
    type: 'GET',
    url:"{{url('/getProductSubStock')}}"+"/"+id,
    success:function(response){
      $('.proId').val(response.pro_code);
      $('.qtySubStock').val(response.qtySubStock);
        if(response.tmp_pro_qty!=null){
          $('.tmp_pro_qty').val(response.tmp_pro_qty);
        }else{
          $('.tmp_pro_qty').val(0);
        }
      },
      error:function(error){
        console.log(error);
      }
  });
}
//----------------------------------
$( ".price" ).keyup(function() {
  var price = null;
  var qty = $('.qty').val();
  price = $('.price').val();
  if(price<0 || price==""){
    $('.add').attr('disabled','true');
    $('.price').css('border','1px solid red');
    $('.amount').val(0);
  }else{
    $('.add').removeAttr('disabled','true');
    $('.price').css('border','1px solid lightblue');
    var qty = $('.qty').val();
    var total = qty * price;
    var amount = total.toFixed(2);
    $('.amount').val(amount);
  }
  if(qty==""){
    $('.add').attr('disabled','true');
    $('.qty').css('border','1px solid red');
    $('.amount').val(0);
  }
});
//----------------------------------
 $( ".qty" ).keyup(function() {
   var qtys = $('.qty').val();
   var qty_pro_in_stocks = $('.qtySubStock').val();
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
    var price = $('.price').val();
    if(price==''){
    $('.add').attr('disabled','true');
    $('.price').css('border','1px solid red');
    $('.amount').val(0);
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
  function getPopupCusSD(){
      $.ajax({
      url:"{{url('/getPopupCusSD')}}",
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
// function getValueCombo(id,ul,f)
// {
//    $.ajax({
//     method: 'GET',
//       url: ul+id,
//       success:function(response){
//         console.log(response)
//         if(Array.isArray(response)){

//             $(f).empty();
//             var serialnumber="<option value=''>---Please select option---</option>";
//             $(f).append(serialnumber);
//             response.map(function(item){
//               console.log(item.name);
//               serialnumber="<option value=" + item.id + ">" + item.name + "</option>";;
//               $(f).append(serialnumber);
//             });
//         }
//       },
//       error:function(error){
//         console.log(error);
//       }
//    })
// };
// //-----------------------
function getEmailCustomer(id){
    $.ajax({
      method: 'GET',
      url:"{{url('/getCustomer')}}"+"/"+id,
      success:function(response){
        $('.contactNo').val(response[0].contactNo);
        $('.cusname').val(response[0].name);
        //$('.channel').val(response[1].description);
      },
      error:function(error){
        console.log(error);
      }
    });
  }
   //-------------------
  function addOrderSDSale(){
  var proid =$(".productId").val();
  var qty = $(".qty").val();
  var price =$(".price").val();
  var amount = $(".amount").val();
  //console.log([scores,studentid]);
  $.ajax({
    url:"{{url('/addOrderSDSale')}}"+"/"+proid+"/"+qty+"/"+price+"/"+amount,
    type:'get',
    dataType: 'json',
    success:function(data){
      $('.add').attr('disabled','true');
      $('#showto').fadeIn();
      $(".productId").val('');
      $('.proId').val(null);
      $('.qty').val(null)
      $('.qty').attr('readonly','readonly');
      $('.price').attr('readonly','readonly');
      $(".price").val(0);
      $(".amount").val(0);
      $(".total").val(1000);
      $('#discount').fadeIn(1000);
      $('#cod').fadeIn(1000);
      $('#btn_hide').removeAttr('disabled','true');
    $('.btn_hide').removeAttr('disabled','true');
      showProductCussd();
      getTotalCussd();
    },
    error:function(error){
      console.log(error)
    },
  });
}
 //-------------------------
  function showProductCussd(){
    $.ajax({
  url:"{{url('/showProductCussd')}}",
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
  function getTotalCussd() {
$.ajax({
  url:"{{url('/getTotalCussd')}}",
  type:'get',
  success:function(data){
      
  var grandTotalcus =0;
  var totalcus = 0;
  totalcus = data;
  total = data.toFixed(2);
  $('.totalcus').val(total);
  $('#showto').fadeIn(1000);
  $('.columnhide').fadeIn(1000);
  $('.showCheckbox').fadeIn(1000);
  $('#MyProList').fadeIn(1000);
  $('.discountcus').val(0);
  $('.grandTotalcus').val(total);
  $( ".discountcus" ).keyup(function() {
            $('.cod').val(0);
            var dis = $(this).val();
            if(dis<0){
                $('.discountcus').css('border','1px solid red');
                $('#btn_hide').attr('disabled','true');
                $('.grandTotalcus').val(0);
            }else if(dis>100){
                $('.discountcus').css('border','1px solid red');
                $('#btn_hide').attr('disabled','true');
                $('.grandTotalcus').val(0);
            }else{
                $('#btn_hide').removeAttr('disabled','true');
                 $('.discountcus').css('border','1px solid lightblue');
                graTotalcus = totalcus - (totalcus * dis)/100;
                grandTotalcus = graTotalcus.toFixed(2);
                $('.grandTotalcus').val(grandTotalcus);
            }
        });
  $( ".cod" ).keyup(function() {
            var dis = $('.discountcus').val();
            var cod = $(this).val();
            if(cod<0){
                $('.cod').css('border','1px solid red');
                $('#btn_hide').attr('disabled','true');
                $('.grandTotalcus').val(0);
            }else if(cod>100){
                $('.cod').css('border','1px solid red');
                $('#btn_hide').attr('disabled','true');
                $('.grandTotalcus').val(0);
            }else{
                $('.cod').css('border','1px solid lightblue');
            }
            if(cod>=0 && cod<100 && dis>0 && dis <100){
              $('#btn_hide').removeAttr('disabled','true');
                $('.cod').css('border','1px solid lightblue');
                var dis = $('.discountcus').val();
                graTotal = totalcus - (totalcus * dis)/100;
                grandTotal = graTotal - (graTotal * cod)/100;
                grandTotalcus = grandTotal.toFixed(2);
                $('.grandTotalcus').val(grandTotalcus);
            }
        });
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
function removeOrderCussd(id){
  $.ajax({
    method: 'GET',
    url:"{{url('/removeOrderCussd')}}"+"/"+id,
    success:function(data){
      $(".productId").val('');
      $('.proId').val(null);
      $('.qty').val(null)
      $('.qty').attr('readonly','readonly');
      $(".price").val(0);
      $(".amount").val(0);
      getTotalCussd(); 
      var count = $('table tr').length;
      if(count==2){
      $('#btn_hide').attr('disabled','true');
      $('.btn_hide').attr('disabled','true');
      $('#discount').fadeOut('slow');
      $('#cod').fadeOut('slow');
     }
      $(".table tr#"+data).remove();
      $('.cod').attr('checked',false);
      $('.discountcus').val(0);
      $('.cod').val(0);
      
    },
    error:function(error){
      console.log(error)
    },
  });
}
//------------------------
// $(document).ready(function(){
// $('.cod').on('change',function(){
//   if (this.checked) {
//     var totalcus =0;
//     var grandTotalcus =0;
//     var totalcus = $('.totalcus').val();
//     var codcus = $('#codcus').val();
//     var discountcus = $('.discountcus').val();
//       if(discountcus!=''){
//         var totaldis = totalcus - totalcus * discountcus/100;
//         var grandTotal = totaldis - totaldis * codcus/100;
//             grandTotalcus = grandTotal.toFixed(2);
//          $('.grandTotalcus').val(grandTotalcus);
//       }else{
//         var codcus = $('#codcus').val();
//         var totalcus = $('.totalcus').val();
//         var grandTotal = totalcus - (totalcus * codcus)/100;
//             grandTotalcus = grandTotal.toFixed(2);
//         $('.grandTotalcus').val(grandTotalcus);
//       }
//   } else {
//       var dis = $('.discountcus').val();
//       var totalcus = $('.totalcus').val();
//       var grandTotal = totalcus - (totalcus * dis)/100;
//           grandTotalcus = grandTotal.toFixed(2);
//       $('.grandTotalcus').val(grandTotalcus);
      
//     }
//  });
// });
   
</script>

@stop