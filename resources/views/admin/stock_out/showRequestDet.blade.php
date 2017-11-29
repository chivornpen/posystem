
<table class="table table-bordered">
    <head>
        <tr>
            <th style="text-align: center;">Product Code</th>
            <th style="text-align:center;">Product Name</th>
            <th style="text-align: center;">Quantities</th>
        </tr>
    </head>
    <tbody>
    @foreach($requstProDetail as $D)
        @foreach($D->products as $detail)
        <tr>
            <td style="font-family: 'Times New Roman';text-align: center; font-size: 12px;">{!! $detail->product_code !!}</td>
            <td style="font-family: 'Khmer OS System'; font-size: 12px;">{!! $detail->name !!}</td>
            <td style="text-align: center; font-size: 12px;">{!! $detail->pivot->qty !!}</td>
        </tr>
        @endforeach
    @endforeach
    </tbody>
</table>

{!! Form::submit('Export',['class'=>'button btn-primary']) !!}