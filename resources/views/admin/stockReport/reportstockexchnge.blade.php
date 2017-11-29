@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading">Stock Exchange Report</div>
        <div class="panel-body table-responsive">
          <div class="container">
            <div class='col-md-3'>
              <div class="form-group">
                <div class='input-group date' id='StartDate' data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input type='text' class="form-control startdate" placeholder="Begin Date">
                    <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span></span>
                </div>
              </div>
            </div>
           <div class='col-md-3'>
              <div class="form-group">
                <div class='input-group date' id='EndDate' data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input type='text' class="form-control enddate" placeholder="End Date">
                    <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span></span>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <button type="button" class="btn btn-default" onclick="saerch()"> Search </button>
            </div>
        </div>
          <div class="content" style="margin-top: 20px;">
          <table border="0px" width="100%">
            <tr>
              <td width="30%">
                <img src="{{url('/images/Logo.JPG')}}" alt="" style="height:20px; float: left;">
              </td>
              <td width="30%">
                <div style="text-align: center; font-weight: bold; font-size: 15px;font-family: 'Khmer OS System';color: blue">Report Stock Exchange</div>
              </td>
              <td width="30%" style="height: 25px;">
               
              </td>
            </tr>
            <tr>
              <td>
                
              </td>
              <td>
                <div style="text-align: center;font-size: 12px;font-family: 'Khmer OS System';"></b></div>
              </td>
              <td>
                
              </td>
            </tr>
          </table>
          <div style="margin-top: 10px;margin-bottom: 5px;font-size: 12px;font-family: 'Khmer OS System';">Reported By: <b>{{Auth::user()->nameDisplay}}</b></div>
          <table width="1600px" class="table-responsive" border="1px" style="border: 1px solid gray; border-collapse: collapse;" cellpadding="5px" cellspacing="0">
            <thead>
              <tr>
                <th colspan="5" style="border-top: 1px solid #fff;border-left: 1px solid #fff;"></th>
                <th colspan="{{$products->count()}}" style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Product Code</th>
              </tr>
              <tr>
                <th style="text-align: center;font-size: 11px;font-weight: bold;height: 30px; padding: 2px 5px; font-family: 'Arial';">No</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Exchange Date</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Invoice Number</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Customer Number</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Customer Name</th>
                @foreach($products as $pro)
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">{{$pro->product_code}}</th>
                @endforeach
              </tr>
            </thead>
            <?php $no=1;?>
            <tbody>
              @foreach($echanges as $echange)
              <tr>
                <td style="text-align: center;font-size: 10px; height: 20px; font-family: 'Arial';">{{$no++}}</td>
                <td style="text-align: center;padding-left: 3px;font-size: 10px;height: 20px; font-family: 'Arial';">{{Carbon\Carbon::parse($echange->created_at)->format('d-M-Y')}}</td>
                <td style="text-align: center;font-size: 10px; height: 20px; font-family: 'Arial';">
                  <?php 
                        echo "CAM-IN-" . sprintf('%06d',$echange->purchaseorder->id);
                  ?>
                </td>
                @if($echange->purchaseorder->customer_id!=null)
                  <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">{{$echange->purchaseorder->customer->id}}</td>
                  <td style="padding-left: 3px;font-size: 10px;height: 20px; font-family: 'Arial';">{{$echange->purchaseorder->user->name}}</td>
                @else
                  <?php 
                    $phone = App\User::where('id','=',$echange->purchaseorder->user_id)->value('contactNum');
                    $customer_id = App\Customer::where('contactNo','=',$phone)->value('id');
                    $customer_name = App\Customer::where('id','=',$customer_id)->value('name');
                    echo "<td style='text-align: center;font-size: 10px;height: 20px; font-family: Arial;'>" ."CAM-CUS-" . sprintf('%06d',$customer_id)."</td>";
                    echo "<td style='padding-left: 3px;font-size: 10px;height: 20px; font-family: Arial;'>" .$customer_name . "</td>";
                  ?>
                @endif
                @foreach($products as $pro)
                  <?php 
                  $product_id =0;
                  $qty =0;
                      $purchaseorders = DB::table('purchaseorder_product')->where([['purchaseorder_id','=',$echange->purchaseorder_id],['product_id','=',$pro->id],])->get();
                    foreach ($purchaseorders as $purchaseorder) {
                      $product_id = $purchaseorder->product_id;
                      $qty = $purchaseorder->qty;
                    }
                 ?>
                  @if($pro->id==$product_id)
                    <td style="text-align: center;font-size: 10px;height: 20px; color: red; font-family: 'Arial';">{{$qty}}</td>
                  @else
                    <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial'; ">0</td>
                  @endif
                @endforeach
              </tr>
              @endforeach
            </tbody>
          </table>
          </div>
          <div style="margin-top: 10px;">
            <button class="btn btn-primary btn-xs" name="print" id="print" value=" Print "><span class="glyphicon glyphicon-print"></span> Print</button>
            <button class="btn btn-success btn-xs" name="btnExportExcel" id="btnExportExcel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button>
          </div>
        </div>
    </div>
  </div>
</div>
@stop
@section('script')
  <script src="{{asset('js/js.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('js/printThis.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    $("#print" ).click(function() {
      $('.content').printThis({
        loadCSS: "",
    });
    });

    $("[id$=btnExportExcel]").click(function(e) {
                window.open('data:application/vnd.ms-excel,' + encodeURIComponent( $('div[class$=content]').html()));
                e.preventDefault();
            });
    $(function () {
            $('#StartDate').datetimepicker({
                language:  'en',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0
            });
            $('#EndDate').datetimepicker({
                language:  'en',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0
            });
        });
    function saerch() {
              var startDate = $(".startdate").val();
              var endDate = $(".enddate").val();
              
              if(startDate=='' && endDate ==''){
                $('.startdate').css('border','1px solid red');
                $('.enddate').css('border','1px solid red');
              }else if(startDate != ''&& endDate ==''){
                $('.enddate').css('border','1px solid red');
                $('.startdate').css('border','1px solid lightblue');
              }else if(endDate !=''&&startDate ==''){
                $('.enddate').css('border','1px solid lightblue');
                $('.startdate').css('border','1px solid red');
              }else{
                $.ajax({
                  type : 'get',
                  url : "{{url('/saerchDateStockExchange')}}"+"/"+startDate+"/"+endDate,
                  dataType: 'html',
                  success:function (data) {
                    $('.startdate').css('border','1px solid lightblue');
                    $('.enddate').css('border','1px solid lightblue');
                    $('.content').html(data);
                  },
                  error:function (error) {
                    console.log(error);
                  }
                });
              }
            }
  </script>
@stop
