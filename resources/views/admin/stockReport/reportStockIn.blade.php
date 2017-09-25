@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading">Stock In Report</div>
        <div class="panel-body table-responsive">
          <div class="container">
            <div class='col-md-4'>
                <div class="form-group">
                    <div class='input-group date' id='datetimepicker6'>
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class='col-md-4'>
                <div class="form-group">
                    <div class='input-group date' id='datetimepicker7'>
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
              <button type="button" class="btn btn-primary"> Search </button>
            </div>
        </div>
          <div class="content">
          <table border="0px" width="100%">
            <tr>
              <td width="30%">
                <img src="{{url('/images/Logo.JPG')}}" alt="" style="height:60px; float: left;">
              </td>
              <td width="30%">
                <div style="text-align: center; font-weight: bold; font-size: 15px;font-family: 'Khmer OS System';">Report Stock In</div>
              </td>
              <td width="30%" style="height: 25px;">
               
              </td>
            </tr>
            <tr>
              <td>
                
              </td>
              <td>
                <div style="text-align: center;font-size: 12px;font-family: 'Khmer OS System';">Monthly Report <b>{{Carbon\Carbon::parse(\Carbon\Carbon::now())->format('d-M-Y')}}</b></div>
              </td>
              <td>
                
              </td>
            </tr>
          </table>
          <div style="margin-bottom: 5px;font-size: 12px;font-family: 'Khmer OS System';">Reported By: <b>{{Auth::user()->nameDisplay}}</b></div>
          <table width="1800px" class="table-responsive" border="1px" style="border: 1px solid gray; border-collapse: collapse;" cellpadding="5px" cellspacing="0">
            <thead>
              <tr>
                <th colspan="7" style="border-top: 1px solid #fff;border-left: 1px solid #fff;"></th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Promotion</th>
                <th colspan="{{$products->count()}}" style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Product Code</th>
              </tr>
              <tr>
                <th style="text-align: center;font-size: 11px;font-weight: bold;height: 30px; padding: 2px 5px; font-family: 'Arial';">No</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Invoice Number</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Invoice Date</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Import Date</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Company Name</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Person Name</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Total Amount</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Discount</th>
                @foreach($products as $pro)
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">{{$pro->product_code}}</th>
                @endforeach
              </tr>
            </thead>
            <?php $no=1;?>
            <tbody>
              @foreach($import as $in)
              <tr>
                <td style="text-align: center;font-size: 10px; height: 20px; font-family: 'Arial';">{{$no++}}</td>
                <td style="text-align: center;font-size: 10px; height: 20px; font-family: 'Arial';">{{$in->invoiceNumber}}</td>
                <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">{{Carbon\Carbon::parse($in->invoiceDate)->format('d-M-Y')}}</td>
                <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">{{Carbon\Carbon::parse($in->impDate)->format('d-M-Y')}}</td>
                <td style="padding-left: 3px; font-size: 10px;height: 20px; font-family: 'Khmer OS System';">{{$in->supplier->companyname}}</td>
                <td style="padding-left: 3px;font-size: 10px;height: 20px; font-family: 'Arial';">{{$in->supplier->personname}}</td>
                <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">
                  <?php 
                        echo "$ " . number_format($in->totalAmount,2);
                    ?>
                </td>
                <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">{{$in->discount . " %"}}</td>
                @foreach($products as $pro)
                  <?php 
                  $product_id =0;
                  $qty =0;
                      $histories = DB::table('histories')->where([['importId','=',$in->id],['productId','=',$pro->id],])->get();
                    foreach ($histories as $his) {
                      $product_id = $his->productId;
                      $qty = $his->qty;
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

    //date time
    $(function () {
        $('#datetimepicker6').datetimepicker();
        $('#datetimepicker7').datetimepicker({
            useCurrent: false //Important! See issue #1075
        });
        $("#datetimepicker6").on("dp.change", function (e) {
            $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
        });
        $("#datetimepicker7").on("dp.change", function (e) {
            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
        });
    });
  </script>
@stop
