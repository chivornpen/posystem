@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading">Stock Balance Report</div>
        <div class="panel-body table-responsive">
          <div class="content">
          <table border="0px" width="100%">
            <tr>
              <td width="30%">
                <img src="{{url('/images/Logo.JPG')}}" alt="" style="height:60px; float: left;">
              </td>
              <td width="30%">
                <div style="text-align: center; font-weight: bold; font-size: 15px;font-family: 'Khmer OS System';">Report Stock Balance</div>
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
          <table width="100%" border="1px" style="border: 1px solid gray; border-collapse: collapse;" cellpadding="5px" cellspacing="0">
            <thead>
              <tr>
                <th style="text-align: center;font-size: 12px;font-weight: bold;height: 30px; padding: 2px 5px; font-family: 'Arial';">No</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Item Code</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Bar Code</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Product Name</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Current Qty</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Beginning Qty</th>
                <th style="text-align: center;font-size: 12px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Percent</th>
              </tr>
            </thead>
            <?php $no=1;?>
            <tbody>
              @foreach($productBalance as $balance)
              <tr>
                <td style="text-align: center;font-size: 10px; height: 20px; font-family: 'Arial';">{{$no++}}</td>
                <td style="text-align: center;font-size: 10px; height: 20px; font-family: 'Arial';">{{$balance->product_code}}</td>
                <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">{{$balance->product_barcode}}</td>
                <td style="padding-left: 3px; font-size: 10px;height: 20px; font-family: 'Khmer OS System';">{{$balance->name}}</td>
                <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">{{$balance->qty}}</td>
                <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';"></td>
                <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';"></td>
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
  </script>
@stop
