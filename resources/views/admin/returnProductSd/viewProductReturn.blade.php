<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">View Product Return</h4>
        </div>
        <div class="modal-body">
            <table class="table table-bordered">
                <tr>
                    <th style="text-align: center;">Product Code</th>
                    <th style="text-align: center;">Quantities Ordered</th>
                    <th style="text-align: center;">Quantities Return</th>
                    <th style="text-align: center;">Specific QTY Order </th>
                </tr>
                @foreach($returnpro as $p)
                        <tr>
                            <td style="text-align: center;">{!! \App\Product::where('id',$p->product_id)->value('product_code')!!}</td>
                            <td style="text-align: center;">{!! $p->TQ !!}</td>
                            <td style="text-align: center;">{!! $p->QR !!}</td>
                            <td style="text-align: center;">{!! $p->QO !!}</td>
                        </tr>
                @endforeach
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>