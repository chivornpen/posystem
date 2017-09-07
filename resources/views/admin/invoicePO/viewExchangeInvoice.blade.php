@if($view)
    <table class="table table-bordered" id="exchangeInvoice">
        <thead>
        <th style="text-align: center;">Product Code</th>
        <th style="text-align: center;">Quantities</th>
        <th style="text-align: center;">Expired Date</th>
        </thead>
        <tbody>
            @foreach($view as $v)
                <tr>
                    <td style="text-align: center;">{!! $v->product_code !!}</td>
                    <td style="text-align: center;">{!! $v->pivot->qty !!}</td>
                    <td style="text-align: center;">{!! $v->pivot->expd !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <a href="#" class="btn btn-primary btn-sm" onclick="createInvoice('{{$id}}')">Create</a>
@else
    <h4>No results</h4>
@endif