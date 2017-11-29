  @if($import)
          <table border="0px" width="100%">
            <tr>
              <td width="30%">
                <img src="{{url('/images/Logo.JPG')}}" alt="" style="height:20px; float: left;">
              </td>
              <td width="30%">
                <div style="text-align: center; font-weight: bold; color: blue; font-size: 15px;font-family: 'Khmer OS System';">Report Stock In</div>
              </td>
              <td width="30%" style="height: 25px;">
               
              </td>
            </tr>
            <tr>
              <td>
                
              </td>
              <td>
                <div style="text-align: center;font-size: 12px;font-family: 'Khmer OS System';">Monthly Report From <b style="color:red;">{{Carbon\Carbon::parse($begin)->format('d-M-Y'). " - " . Carbon\Carbon::parse($end)->format('d-M-Y')}}</b></div>
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
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Invoice Number</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Import Date</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Company Name</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Person Name</th>
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
                <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">{{Carbon\Carbon::parse($in->impDate)->format('d-M-Y')}}</td>
                <td style="padding-left: 3px; font-size: 10px;height: 20px; font-family: 'Khmer OS System';">{{$in->supplier->companyname}}</td>
                <td style="padding-left: 3px;font-size: 10px;height: 20px; font-family: 'Arial';">{{$in->supplier->personname}}</td>
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
  @endif