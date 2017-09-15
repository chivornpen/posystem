<!-- Modal -->
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Exchange Detail</h4>
        </div>
        <div class="modal-body">
            <table class="table table-bordered">
                <tr>
                    <th style="text-align: center;">Product Code</th>
                    <th style="text-align: center;">Quantities</th>
                    <th style="text-align: center;">Expired Date</th>
                </tr>
                @foreach($data as $d)

                    <tr>
                        <td style="text-align: center;">{!! $d->product_code!!}</td>
                        <td style="text-align: center;">{!! $d->pivot->qty!!}</td>
                        <td style="text-align: center;">{!! $d->pivot->expd!!}</td>
                    </tr>
                @endforeach
            </table>
                <label style="color:red; font-family:'Times New Roman',Serif;">Customer Name:</label>
                <label style="color:dodgerblue; font-family: 'Khmer OS Siemreap',Serif;"> {{$user_name}}</label>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>