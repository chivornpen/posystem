@if($brandProducts->count())
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
                @if($begin!=null)
                <div style="text-align: center;font-size: 12px;font-family: 'Khmer OS System';">Monthly Report From <b style="color:red;">{{Carbon\Carbon::parse($begin)->format('d-M-Y'). " - " . Carbon\Carbon::parse($end)->format('d-M-Y')}}</b></div>
                @endif
              </td>
              <td>
                
              </td>
            </tr>
          </table>
          <div style="margin-top: 10px;margin-bottom: 5px;font-size: 12px;font-family: 'Khmer OS System';">Reported By: <b>{{Auth::user()->nameDisplay}}</b></div>
          <table width="1500px" class="table-responsive" border="1px" style="border: 1px solid gray; border-collapse: collapse;" cellpadding="5px" cellspacing="0">
            <thead>
              <tr>
                @if(Auth::user()->position->name!='SD')
                <th colspan="7" style="border-top: 1px solid #fff;border-left: 1px solid #fff;"></th>
                @else
                <th colspan="6" style="border-top: 1px solid #fff;border-left: 1px solid #fff;"></th>
                @endif
                <th colspan="{{$brandProducts->count()}}" style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Product Code</th>
              </tr>
              <tr>
                <th style="text-align: center;font-size: 11px;font-weight: bold;height: 30px; padding: 2px 5px; font-family: 'Arial';">No</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Exchange Date</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Invoice Number</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">New Invoice Number</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Customer Number</th>
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Customer Name</th>
                @if(Auth::user()->position->name!='SD')
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">Branch Name</th>
                @endif
                @foreach($brandProducts as $brand_pro)
                <th style="text-align: center;font-size: 11px;font-weight: bold; padding: 2px 5px; font-family: 'Arial';">{!!\App\Product::where('id',$brand_pro->product_id)->value('product_code') !!}</th>
                @endforeach
              </tr>
            </thead>
            <?php $no=1;?>
            <tbody>
              @foreach($exchangesds as $echangesd)
              <tr>
                <td style="text-align: center;font-size: 10px; height: 20px; font-family: 'Arial';">{{$no++}}</td>
                <td style="text-align: center;padding-left: 3px;font-size: 10px;height: 20px; font-family: 'Arial';">{{Carbon\Carbon::parse($echangesd->created_at)->format('d-M-Y')}}</td>
                <td style="text-align: center;padding-left: 3px;font-size: 10px;height: 20px; font-family: 'Arial';">
                    <?php 
                        echo "CAM-IN-" . sprintf('%06d',$echangesd->stockoutsd->purchaseordersd->id);
                  ?>
                </td>
                <td style="text-align: center;font-size: 10px; height: 20px; font-family: 'Arial';">
                  <?php 
                        echo "CAM-IN-" . sprintf('%06d',$echangesd->purchaseordersd->id);
                  ?>
                </td>
                @if($echangesd->purchaseordersd->customer_id!=null)
                  <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial';">{{$echangesd->purchaseordersd->customer->id}}</td>
                  <td style="padding-left: 3px;font-size: 10px;height: 20px; font-family: 'Arial';">{{$echangesd->purchaseordersd->user->name}}</td>
                @else
                  <?php 
                    $phone = App\User::where('id','=',$echangesd->purchaseordersd->user_id)->value('contactNum');
                    $customer_id = App\Customer::where('contactNo','=',$phone)->value('id');
                    $customer_name = App\Customer::where('id','=',$customer_id)->value('name');
                    echo "<td style='text-align: center;font-size: 10px;height: 20px; font-family: Arial;'>" ."CAM-CUS-" . sprintf('%06d',$customer_id)."</td>";
                    echo "<td style='padding-left: 3px;font-size: 10px;height: 20px; font-family: Arial;'>" .$customer_name . "</td>";
                  ?>
                @endif
                 @if(Auth::user()->position->name!='SD')
                <td style="text-align: center;font-size: 10px; padding: 2px 5px; font-family: 'Khmer OS System';">{{$echangesd->brand->brandName}}</td>
                @endif
                @foreach($brandProducts as $brand_pro)
                  <?php 
                  $product_id =0;
                  $qty =0;
                      $purchaseorders = DB::table('purchaseordersd_product')->where([['purchaseordersd_id','=',$echangesd->purchaseordersd_id],['product_id','=',$brand_pro->product_id],])->get();
                    foreach ($purchaseorders as $purchaseorder) {
                      $product_id = $purchaseorder->product_id;
                      $qty = $purchaseorder->qty;
                    }
                 ?>
                  @if($brand_pro->product_id==$product_id)
                    <td style="text-align: center;font-size: 10px;height: 20px; color: red; font-family: 'Arial';">{{$qty}}</td>
                  @else
                    <td style="text-align: center;font-size: 10px;height: 20px; font-family: 'Arial'; ">0</td>
                  @endif
                @endforeach
              </tr>
              @endforeach
            </tbody>
          </table>
@else
<h5>No found results</h5><br>
@endif