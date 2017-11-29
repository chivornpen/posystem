<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Requested Products</h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th style="text-align: center;">Product Code</th>
                        <th >Product Name </th>
                        <th style="text-align: center;">Quantities</th>
                    </tr>
                    @foreach($stockoutre->imports as $sr)
                        <tr>
                            <td style="text-align: center;">{!! \App\Product::where('id',$sr->pivot->product_id)->value('product_code') !!}</td>
                            <td>{!! str_limit(\App\Product::where('id',$sr->pivot->product_id)->value('name'), 43)!!}</td>
                            <td style="text-align: center;">{!! $sr->pivot->qty !!}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>