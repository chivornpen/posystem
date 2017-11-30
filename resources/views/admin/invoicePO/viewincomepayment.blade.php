@extends('layouts.admin')
@section('content')
<div class="container-fluid"><br>
  <div class="panel panel-default">
    <div class="panel-heading">
      PAYMENTS LIST
    </div>
      <div class="panel panel-body">
        <div class="container-fluid table-responsive">
          <div class="re">
          <table width="100%" id="table" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                  <th>No</th>
                  <th>Batch ID</th>
                  <th>Date</th>
                  <th>Cash On Hand</th>
                  <th>COGS</th>
                  <th>Revenue</th>
              </tr>
            </thead>
        <?php $no=1;?>
            <tbody>
              @foreach($transections as $transection)
                <tr>
                    <td>{{$no++}}</td>
                    <td style="font-size: 11px; font-family: 'Khmer OS System';">
                        {{$transection->batchID}}
                    </td>
                    <td style="font-size: 11px; font-family: 'Khmer OS System';">
                      {!!\App\Transection::where('batchID','=',$transection->batchID)->where('chartaccount_id','=',1)->value('transectionDate') !!}
                    </td>
                    <td style="font-size: 11px; font-family: 'Khmer OS System';">
                        {!!\App\Transection::where('batchID','=',$transection->batchID)->where('chartaccount_id','=',1)->sum('drAmt') !!}
                    </td>
                    <td style="font-size: 11px; font-family: 'Khmer OS System';">
                         {!!\App\Transection::where('batchID','=',$transection->batchID)->where('chartaccount_id','=',8)->sum('crAmt') !!}
                    </td>
                    <td style="font-size: 11px; font-family: 'Khmer OS System';">
                         {!!\App\Transection::where('batchID','=',$transection->batchID)->where('chartaccount_id','=',9)->sum('crAmt') !!}
                    </td>
                </tr>
                @endforeach
                <script type="text/javascript">
                    RemoveSpace();
                    function RemoveSpace(){
                            var el = document.querySelector('.re');
                            var doc = el.innerHTML;
                            //alert('Message : ' + doc);
                            el.innerHTML = el.innerHTML.replace(/&nbsp;/g,'');
                      }
                </script>
            </tbody>
          </table>
          </div>
        </div>
      </div>
  </div>
</div>
@stop
@section('script')
<script type="text/javascript">
$(document).ready(function() {
         $('#table').DataTable({
            responsive: true
        });
    });
</script>
@stop