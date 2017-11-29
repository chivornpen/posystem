@if($customerSdCount)
    <img src="{{asset('/images/Logo.jpg')}}" style="height: 15px; width: 110px; margin: 10px 0 10px 0"><br>
    <p style="font-family: 'Times New Roman',Serif;color: #cf3d54; font-size:12px;"><b>CUSTOMER EXPIRED PRODUCTS</b></p>
    <table border="1px" cellpadding="5px" id="expired" style=" width: auto;border-collapse: collapse; border:1px solid #7a7a7a;">
        <thead>
        <tr>
            <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center; padding:2px 8px;">No</td>
            <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Invoices Number</td>
            <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Stock Date</td>
            <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Customer Number</td>
            <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Customer Name</td>
            <td style="font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px;">Products Code</td>
            <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Quantities</td>
            <td style="text-align: center; font-family:'Arial Black',Serif;font-size: 12px; padding:2px 8px;">Expired Date</td>
        </tr>
        </thead>
        <tbody>
        <?php $n =1; ?>
        @foreach($customerSd as $sdCus)
            @foreach($sdCus->subimports()->whereBetween('expd',[\Carbon\Carbon::now()->toDateString('Y-M-d'),\Carbon\Carbon::now()->addYear(1)->toDateString('Y-M-d')])->get() as $subPro)
                <tr>
                    @if($subPro->pivot->qty > 0)
                        <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{$n++}}</td>
                        <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ "INV-".sprintf('%06d',$sdCus->purchaseordersd_id) }}</td>
                        <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ \Carbon\Carbon::parse($sdCus->stockoutDate)->format('d-M-Y')  }}</td>
                        <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ "CUS-".sprintf('%06d',\App\Purchaseordersd::where('id',$sdCus->purchaseordersd_id)->value('customer_id')) }}</td>
                        <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ \App\Customer::where('id',\App\Purchaseordersd::where('id',$sdCus->purchaseordersd_id)->value('customer_id'))->value('name')}}</td>
                        <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ \App\Product::where('id',$subPro->pivot->product_id)->value('product_code') }}</td>
                        <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ $subPro->pivot->qty }}</td>
                        <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{ \Carbon\Carbon::parse($subPro->pivot->expd)->format('d-M-Y') }}</td>

                        {{--\App\Product::where('id',$cu->product_id)->value('product_code')--}}
                        {{--\App\User::where('id',\App\Purchaseorder::where('id',\App\Stockout::where('id',$cu->stockout_id)->value('purchaseorder_id'))->value('user_id'))->value('contactNum')--}}
                    @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <h5>No found results</h5>
@endif