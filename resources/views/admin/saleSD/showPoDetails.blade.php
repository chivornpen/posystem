 @extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-lg-12">                
        <h4 class="page-header"><i class="fa fa-shopping-basket" aria-hidden="true"></i> Purchase Order Details</h4>
    </div>
</div>
<div class="row">
    <div class="col-lg-11">
        <a href="{{ route('saleSD.index')}}" title="Create new order" class="btn btn-info btn-sm" > Back </a>
    </div>
    <div class="col-lg-1">
       
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <table class="table table-responsive" cellspacing="0">
            <thead>
                <tr class="bg-primary">
                    <th>PO ID</th>
                    <th>Date</th>
                    <th>Customer</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-size: 12px; font-weight: bold; font-family: 'Khmer OS System';">
                        {{$details->id}}
                    </td>
                    <td style="font-size: 12px; font-weight: bold; font-family: 'Khmer OS System';">
                        {{Carbon\Carbon::parse($details->poDate)->format('d-M-Y')}}
                    </td>
                    <td style="font-size: 13px; font-weight: bold; font-family: 'Khmer OS System';">
                        {{$details->customer->name}}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <table class="table table-responsive table-bordered table-striped" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Product Code</th>
                <th>Product Barcode</th>
                <th>Product Name</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <?php $n = 1; ?>
        <tbody>
            @foreach($details->products as $detail)
            <tr>
                <td>{{$n++}}</td>
                <td style="font-size: 11px; font-family: 'Khmer OS System';">
                    {{$detail->product_code}}
                </td>
                <td style="font-size: 11px; font-family: 'Khmer OS System';">
                    {{$detail->product_barcode}}
                </td>
                <td style="font-size: 11px; font-family: 'Khmer OS System';">
                    {{$detail->name}}
                </td>
                <td style="font-size: 11px; font-family: 'Khmer OS System';">
                    {{$detail->pivot->qty}}
                </td>
                <td style="font-size: 11px; font-family: 'Khmer OS System';">
                    <?php 
                        echo "$ " . number_format($detail->pivot->unitPrice,2);
                    ?>
                </td>
                <td style="font-size: 11px; font-family: 'Khmer OS System';">
                    <?php 
                        echo "$ " . number_format($detail->pivot->amount,2);
                    ?>
                </td>
            </tr>
            @endforeach
            <tr>
                <td style="border-left:none; border-right:none;border-button:none;"></td>
                <td style="border-left:none; border-right:none;border-button:none;"></td>
                <td style="border-left:none; border-right:none;border-button:none;"></td>
                <td style="border-left:none; border-right:none;border-button:none;"></td>
                <td style="border-left:none; border-button:none;"></td>
                <td>Total Amount</td>
                <td>
                    <?php 
                        echo "$ " . number_format($details->totalAmount,2);
                    ?>
                </td>
            </tr>
            <tr>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td style="border-left:none; border-button:none; border-top:none;"></td>
                <td>Discount</td>
                <td>
                    @if($details->discount!=null)
                        {{$details->discount . " %"}}
                    @else
                        0 %
                    @endif
                </td>
            </tr>
            <tr>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td style="border-left:none; border-button:none; border-top:none;"></td>
                <td>COD</td>
                <td>
                    @if($details->cod!=null)
                        {{$details->cod . " %"}}
                    @else
                        0 %
                    @endif
                </td>
            </tr>
            <tr>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td style="border:none;"></td>
                <td style="border-left:none; border-button:none; border-top:none;"></td>
                <td>Grand Total</td>
                <td>
                    <?php 
                        echo "$ " . number_format($details->grandTotal,2);
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
        </div>
    </div>
</div>
@stop
