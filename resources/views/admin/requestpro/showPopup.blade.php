<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Product Requested</h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th style="text-align: center;">No</th>
                        <th style="text-align: center;">Product Name</th>
                        <th style="text-align: center;">Quantities</th>
                    </tr>
                    <?php $n=1; ?>
                    @foreach($details->products as $detail)
                        <tr>
                            <td style="text-align: center;">{{$n++}}</td>
                            <td style="font-size: 11px; font-family: 'Khmer OS System';">{{$detail->name}}</td>
                            <td style="text-align: center;">{{$detail->pivot->qty}}</td>
                        </tr>
                    @endforeach
                </table>
                <h5>Request By &nbsp;&nbsp;&nbsp;&nbsp;: <b style="color: red;">{!! \App\User::where('id',$details->reqby)->value('nameDisplay')!!}</b></h5>
                <h5>Request Date : <b style="color: red;">{{\Carbon\Carbon::parse($details->reqDate)->format('d-M-Y')}}</b></h5>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
