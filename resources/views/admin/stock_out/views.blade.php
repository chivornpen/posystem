<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Invoice Detail : {{\Carbon\Carbon::parse($purchaseOrder->invoiceDate)->format('d-M-Y')}}</h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th style="text-align: center;">Product Name</th>
                        <th style="text-align: center;">Quantities</th>
                        <th style="text-align: center;">Unit Price</th>
                        <th style="text-align: center;">Amount</th>
                    </tr>
                    @foreach($purchaseOrder->products as $id)
                        <tr>
                            <td style="text-align: center;">{{$id->product_code}}</td>
                            <td style="text-align: center;">{!! $id->pivot->qty !!}</td>
                            <td style="text-align: center;">{!! $id->pivot->unitPrice !!}</td>
                            <td style="text-align: center;">{!! $id->pivot->amount !!}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <h5><smal>Customer : {{$purchaseOrder->customer->name}}</smal></h5>
            <h5><smal>Order By : {{$purchaseOrder->user->name}}</smal></h5>
            <h5><smal>Total Amount : {{"$ ".$purchaseOrder->totalAmount}}</smal></h5>
            <h5><smal>Discount : {{$purchaseOrder->discount}} %</smal></h5>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>