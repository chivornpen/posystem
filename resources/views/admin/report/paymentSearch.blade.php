@if($purchaseorder->count())
    <img src="{{asset('/images/Logo.jpg')}}" style="height: 15px; width: 110px; margin: 10px 0 10px 0"><br>
    <p style="font-family: 'Times New Roman',Serif;color: #cf3d54; font-size:12px;"><b> PAYMENT REPORTS</b></p>

    <table border="1px" cellpadding="5px" id="customer" style=" width: 2500px; border-collapse: collapse; border:1px solid #7a7a7a;">
        <thead>
        <tr>
            <td colspan="10" style="border-top: 1px solid white; border-left: 1px solid white;"></td>
            <td colspan="{{$product->count()*2}}" style=" font-family:'Arial Black',Serif;font-size: 12px; text-align: center; padding: 3px;">Product Code</td>
        </tr>
        <tr>
            <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center; padding:2px 8px;">No</td>
            <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Customer ID</td>
            <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Customer Name</td>
            <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px; padding:2px 8px;">Invoice Number</td>
            <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px; padding:2px 8px;">Status</td>
            <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Total Amount</td>
            <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Discount (%)</td>
            <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px;">COD (%)</td>
            <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px; padding:2px 8px;">Paid Date</td>
            <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px;">Paid</td>
            @if($product->count())
                @foreach($product as $pro)
                    <td style="font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px; text-align: center; padding: 5px;" colspan="2">{{$pro->product_code}}</td>
                @endforeach
            @endif
        </tr>
        </thead>
        <tbody>
        <?php $i=1;?>
        @foreach($purchaseorder as $purchase)
            <tr>
                <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{$i++}}</td>
                {{--<td style="text-align: center; font-family: 'Times New Roman'; font-size: 12px;padding:2px 8px;">{!! \Carbon\Carbon::parse($purchase->poDate)->format('d-M-Y') !!}</td>--}}
                {{--<td style="font-family: 'Khmer OS System',Serif;font-size: 12px;padding:2px 8px;">{!!strtoupper(\App\User::where('id',$purchase->user_id)->value('nameDisplay')) !!}</td>--}}
                <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $purchase->customer_id ? "CAM-CUS-".sprintf('%06d',$purchase->customer_id) : "CAM-CUS-".sprintf('%06d',\App\Customer::where('contactNo','=',\App\User::where('id',$purchase->user_id)->value('contactNum'))->value('id')) !!}</td>
                <td style=" font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $purchase->customer_id ? \App\Customer::where('id',$purchase->customer_id)->value('name') : \App\User::where('id',$purchase->user_id)->value('nameDisplay') !!}</td>
                <td style=" font-family: 'Khmer OS System',Serif;font-size: 12px;padding:2px 8px; text-align: center;">{{ "CAM-IN-".sprintf('%06d', $purchase->id)}}</td>

                @if("comp" ==strtolower($purchase->status))
                    <td style=" font-family: 'Khmer OS System',Serif;font-size: 12px;padding:2px 8px; text-align: center; color:red;">{!! "Company Paid"  !!}</td>
                @elseif("cusp"==strtolower($purchase->status))
                    <td style=" font-family: 'Khmer OS System',Serif;font-size: 12px;padding:2px 8px; text-align: center; color:red;">{!! "Customer Paid"  !!}</td>
                @elseif($purchase->id == \App\Exchange::where('purchaseorder_id',$purchase->id)->value('purchaseorder_id'))
                    <td style=" font-family: 'Khmer OS System',Serif;font-size: 12px;padding:2px 8px; text-align: center; color: #db4f13;">{!! "Exchange"  !!}</td>
                @elseif("a" == strtolower(\App\Returnpro::where('stockout_id',\App\Stockout::where('purchaseorder_id',$purchase->id)->value('id'))->value('status')))
                    <td style=" font-family: 'Khmer OS System',Serif;font-size: 12px;padding:2px 8px; text-align: center; color: red;">{!! "Returned All"  !!}</td>
                @else
                    <td style=" font-family: 'Khmer OS System',Serif;font-size: 12px;padding:2px 8px; text-align: center; color: #0d6aad;">{!! "Ordered"  !!}</td>
                @endif

                <td style="font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px; text-align: center;">{!! "$ ". number_format($purchase->totalAmount,2) !!}</td>
                <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $purchase->discount!!}</td>
                <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $purchase->cod !!}</td>
                <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! \Carbon\Carbon::parse($purchase->paidDate)->format('d-M-Y') !!}</td>
                <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! "$ ". number_format($purchase->paid,2) !!}</td>
                @foreach($product as $prod)
                    <?php
                    $proId = "";
                    $proQty="";
                    $unitPrice="";
                    $products = DB::table('purchaseorder_product')->where([['purchaseorder_id','=',$purchase->id],['product_id','=',$prod->id],])->get();
                    foreach($products as $row){
                        $proId = $row->product_id;
                        $proQty= $row->qty;
                        $unitPrice = $row->unitPrice;
                    }
                    ?>
                    @if($prod->id == $proId)
                        <td scope="col" style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px; color: red;">{!! "Q ". $proQty !!}</td>
                        <td scope="col" style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px; color: red;">{!!"P ". $unitPrice !!}</td>
                    @else
                        <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! "0" !!}</td>
                        <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! "0" !!}</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

    @else
        <h5>No found results</h5><br>
    @endif