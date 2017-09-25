@if($returnpro)
    <table class="table table-bordered" id="ProductReturn">
        <thead>
            <th style="text-align: center;">Product Code</th>
            <th style="text-align: center;">Quantities Ordered</th>
            @if($status ==1)
                <th style="text-align: center;">Quantities Return</th>
            @else
                <th style="text-align: center;">Specific QTY Ordered</th>
            @endif
        </thead>
        <tbody>
            @foreach($returnpro as $re)
                <tr>
                    <td style="text-align: center;">{!! \App\Product::where('id',$re->product_id)->value('product_code') !!}</td>
                    <td style="text-align: center;">{!! $re->TQ !!}</td>
                    @if($status ==1)
                        <td style="text-align: center;">{!! $re->QR !!}</td>
                        @else
                        <td style="text-align: center;">{!! $re->QO !!}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="#" class="btn btn-primary btn-sm" onclick="ProductReturCreate('{{$returnId}}')">Create</a>
@else
    <h5>No Found results</h5>
@endif