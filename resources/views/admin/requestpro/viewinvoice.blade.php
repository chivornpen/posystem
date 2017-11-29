@if($data)
    <div class="row">
        <div class="col-lg-12">
            <label style="color:red; font-family:'Times New Roman',Serif;">Request By:</label>
            <label style="color:dodgerblue; font-family: 'Khmer OS Siemreap',Serif;">{!! \App\User::where('id',$details->reqby)->value('nameDisplay')!!} </label>
            <br>
            <label style="color:red; font-family:'Times New Roman',Serif;">Request Date:</label>
            <label style="color:dodgerblue; font-family: 'Khmer OS Siemreap',Serif;">{{$details->reqDate}} </label>
            <br>
            <label style="color:red; font-family:'Times New Roman',Serif;">Description:</label>
            <label style="color:dodgerblue; font-family: 'Khmer OS Siemreap',Serif;"> {{$details->status}}</label>
        </div>


        <div class="col-lg-3">
            <div class="checkbox checkbox-primary">
                <input id="ckreturnAll" type="checkbox" value="1" onclick="ckReturnall()">
                <label for="ckreturnAll" style="color:dodgerblue; font-family: 'Khmer OS Siemreap',Serif;">
                   Return All
                </label>
            </div>
        </div>


    </div>
    <br>
    <table width="980px" class="table table-bordered" style="box-shadow: 2px 1px 3px 0px gray" id="returnInvoice">
        <thead>
        <tr>
            <th style="text-align: center">No</th>
            <th style="text-align: center">Product Name</th>
            <th style="text-align: center">Quantities</th>
            <th style="text-align: center">Expired Date</th>
            <th style="text-align: center">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1 ?>
        @foreach($data as $row)
            @if($row->pivot->status !=1)
                <tr>
                    <td style="text-align: center">{!! $i++ !!}</td>
                    <td style="font-size: 11px; font-family: 'Khmer OS System';">{!! \App\Product::where('id','=',$row->pivot->product_id)->value('name') !!}</td>
                    <td style="text-align: center">{{$row->pivot->qty}}</td>
                    <td style="text-align: center">{{Carbon\Carbon::parse($row->pivot->expd)->format('d-M-Y')}}</td>
                    <td style="text-align: center">
                        <a href="#" class="btn btn-info btn-xs" id="btnReturn" onclick="Return('{{$row->id}}','{{$row->pivot->product_id}}','{{$row->pivot->expd}}','{{$row->pivot->qty}}','{{$stockoutreID}}')" data-toggle="modal" data-target="#returnProduct">Return</a>
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>

    </table>

    <div class="row" id="btnReturnAll" style="display: none;">
        <div class="col-lg-12">
            <div class="form-group">
                <a href="#" class="btn btn-primary btn-sm" onclick="ReturnAll('{{$stockoutreID}}')" style="width: 90px;">Return</a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="returnProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Return Products</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="number" name="returnQty" id="returnQty" placeholder="Quantities" class="form-control" onkeyup="checkQty()">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn_save"  onclick="SaveReturn()" data-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
@else
    <h5>No results </h5>
@endif



