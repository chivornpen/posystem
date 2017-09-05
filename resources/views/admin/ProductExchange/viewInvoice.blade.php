@if($data)
    <br>
    <div><h4>Invoice Detail</h4></div>
    <table class="table table-bordered" style="box-shadow: 2px 1px 3px 0px gray" id="Exchange">
        <thead>
        <tr>
            <th style="text-align: center">No</th>
            <th style="text-align: center">Product Code</th>
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
                    <td style="text-align: center">{!! \App\Product::where('id','=',$row->pivot->product_id)->value('product_code') !!}</td>
                    <td style="text-align: center">{!! $row->pivot->qty !!}</td>
                    @if($row->pivot->expd <= \Carbon\Carbon::now()->addYear(1)->toDateString())
                        <td style="text-align: center; color: red">{!! $row->pivot->expd !!}</td>
                        <td style="text-align: center">
                            <a href="#" class="btn btn-warning btn-xs" onclick="Exchange('{{$row->id}}','{{$row->pivot->product_id}}','{{$row->pivot->expd}}','{{$row->pivot->qty}}','{{$stockoutID}}')" data-toggle="modal" data-target="#myModal">Exchange</a>
                         </td>
                    @else
                        <td style="text-align: center;">{!! $row->pivot->expd !!}</td>
                        <td style="text-align: center">
                            <a href="#" class="btn btn-danger btn-xs" disabled>Exchange</a>
                        </td>
                    @endif

                </tr>
            @endif
        @endforeach
        </tbody>

    </table>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Exchange Products</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="number" name="exQty" id="exQty" placeholder="Quantities" class="form-control" onkeyup="checkQty()">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="SaveRecord()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@else
    <h5>No results </h5>
@endif



