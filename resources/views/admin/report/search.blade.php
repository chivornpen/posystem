   @if($customer)
        <img src="/images/Logo.jpg" style="height: 15px; width: 110px;">
        <p style="font-family: 'Times New Roman',Serif;color: #cf3d54; font-size:12px;"><b>CUSTOMER LIST</b></p>
        <table border="1px" cellpadding="5px" id="customer" style=" width: 1600px; border-collapse: collapse; border:1px solid #7a7a7a;">
            <thead>
            <tr>
                <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">No</td>
                <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Customer Code</td>
                <td style="font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px;">Customer Name</td>
                <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Customer Chanel</td>
                <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Customer Contact</td>
                <td style="font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px; ">Territory</td>
                <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Home Number</td>
                <td style="font-family:'Arial Black',Serif;font-size: 12px; text-align: center;padding:2px 8px;">Street</td>
                <td style="font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px;">Commune</td>
                <td style="font-family:'Arial Black',Serif;font-size: 12px;padding:2px 8px;">Location</td>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($customer as $c)
                <tr>
                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{{$i++}}</td>
                    <td style="text-align: center; font-family: 'Times New Roman'; font-size: 12px;padding:2px 8px;">{{"CAM-CU-".sprintf('%06d',$c->id)}}</td>
                    <td style="font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $c->name !!}</td>
                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! \App\Channel::where('id',$c->channel_id)->value('description') !!}</td>
                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $c->contactNo !!}</td>
                    <td style=" font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! \App\Province::where('id',$c->province_id)->value('name') ?  \App\Province::where('id',$c->province_id)->value('name') : "N/A" !!}</td>
                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $c->homeNo ? $c->homeNo : "N/A" !!}</td>
                    <td style="text-align: center; font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $c->streetNo ? $c->streetNo : "N/A" !!}</td>
                    <td style="font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $c->commune_id ? \App\Commune::where('id',$c->commune_id)->value('name') : "N/A" !!}</td>
                    <td style="font-family: 'Times New Roman',Serif;font-size: 12px;padding:2px 8px;">{!! $c->location ? $c->location : "N/A" !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
   @else
       <h5>No found results</h5>
   @endif