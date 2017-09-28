@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading">Stock Balance Report</div>
        <div class="panel-body table-responsive">
          {{---for admin select---}}
            @if(Auth::user()->position->name!='SD')
            <div class='col-md-12'>
              <div class="form-group">
                <select name="brands" class="form-control brands">
                  <option value="0">Please select brand name</option>
                    @foreach($brands as $brand)
                      <option value="{{$brand->id}}">{!! $brand->brandName !!}</option>
                    @endforeach
                </select>
              </div>
            </div>
            @endif
          <div class="content" style="margin-top: 20px;">
          @if(Auth::user()->position->name =='SD')
          <table border="0px" width="1300">
            <tr>
              <td width="30%">
                <img src="{{url('/images/Logo.JPG')}}" alt="" style="height:20px; float: left;">
              </td>
              <td width="30%">
                <div style="text-align: center; font-weight: bold; font-size: 15px;font-family: 'Khmer OS System'; color: blue;">Report Stock Balance</div>
              </td>
              <td width="30%" style="height: 25px;">
               
              </td>
            </tr>
            <tr>
              <td>
                
              </td>
              <td>
                <div style="text-align: center;font-size: 12px;font-family: 'Khmer OS System';">Monthly Report <b style="color: red;">{{Carbon\Carbon::parse(\Carbon\Carbon::now())->format('d-M-Y')}}</b></div>
              </td>
              <td>
                
              </td>
            </tr>
          </table>
          <div style="margin-bottom: 5px;font-size: 12px;font-family: 'Khmer OS System';">Reported By: <b>{{Auth::user()->nameDisplay}}</b></div>
          <table width="100%" border="1px" style="border: 1px solid gray; border-collapse: collapse;" cellpadding="5px" cellspacing="0">
            <thead>
              <tr>
                <th colspan="4" style="border-top: 1px solid #fff;border-left: 1px solid #fff;"></th>
                <th colspan="2" style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial'; background-color: blue; color: white;">Begining Stock</th>
                <th colspan="2" style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';background-color: green; color: white;">Stock Out</th>
                <th colspan="2" style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';background-color: red; color: white;">Current Stock</th>
              </tr>
              <tr>
                <th style="text-align: center;font-size: 12px;font-weight: bold;height: 30px; padding: 2px 5px; font-family: 'Arial';">No</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Item Code</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Bar Code</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Product Name</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Qty</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Percents</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Qty</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Percents</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Qty</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Percents</th>
              </tr>
            </thead>
            <?php $no=1;?>
            <tbody>
              @foreach($brandProducts as $balance)

                <tr>
                  <td style="text-align: center;font-size: 10px; height: 20px; font-family: 'Arial';">{{$no++}}</td>
                  <td style="text-align: center;font-size: 10px; height: 20px; font-family: 'Arial';">  {!!\App\Product::where('id',$balance->product_id)->value('product_code') !!}
                  </td>
                  <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">
                    {!!\App\Product::where('id',$balance->product_id)->value('product_barcode') !!}
                  </td>
                  <td style="padding-left: 3px; font-size: 10px;height: 20px; font-family: 'Khmer OS System';">{!!\App\Product::where('id',$balance->product_id)->value('name') !!}</td>
                    <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial'; ">
                  
                  <?php
                    $qty = ''; 
                    foreach ($purchaseorders as $po) {
                      $purchaseorder = DB::table('purchaseorder_product')->where([['purchaseorder_id','=',$po->id],['product_id','=',$balance->product_id],])->get();
                      foreach ($purchaseorder as $pur) {
                        $product_id = $pur->product_id;
                        $qty += $pur->qty;
                      }
                    }
                    echo $qty;
                  ?>
                  </td>
                  <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">100.00 %</td>
                  <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">{{$qty-$balance->qty}}</td>
                  @if($qty!=0)
                  <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">                      <?php
                      echo number_format(($qty-$balance->qty)*100/$qty,2) . " %";
                    ?>
                  </td>
                  @else
                  <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">0.00 %</td>
                  @endif
                  <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">{{$balance->qty}}</td>
                  @if($qty!=0)
                  <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">                      <?php
                      echo number_format(($balance->qty)*100/$qty,2) . " %";
                    ?>
                  </td>
                  @else
                  <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">0.00 %</td>
                  @endif
                </tr>
              @endforeach
            </tbody>
          </table>
          @endif
          </div>
          <div class="col-md-12" style="margin-top: 20px;">
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
      //--------------------------
  $('.brands').on('change',function(e){
    $('.brands').css('border','1px solid lightblue');
      var brand_id = $(".brands").val();
      if(brand_id==0){
        $.ajax({
            type : 'get',
            url : "{{url('/searchBalance')}}"+"/"+brand_id,
            dataType: 'html',
            success:function (data) {
            $('.content').html(data);
            },
            error:function (error) {
            console.log(error);
            }
        });
      }else{
        $.ajax({
            type : 'get',
            url : "{{url('/searchBalance')}}"+"/"+brand_id,
            dataType: 'html',
            success:function (data) {
            $('.content').html(data);
            },
            error:function (error) {
            console.log(error);
            }
        });
      }
  });
  //--------------------
  </script>
@stop
