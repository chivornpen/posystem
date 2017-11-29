
  @if($tmpdata->count())

              <table width="980px" class="table table-bordered table-striped" cellspacing="0">
                  <thead>
                      <tr>
                          <th>No</th>
                          <th>Product Name</th>
                          <th>Quantity</th>
                          <th>Actions</th>
                      </tr>
                  </thead>
                  <?php $no=1;?>
                  <tbody>
                      @foreach($tmpdata as $tmppo)
                      <tr id="{{$tmppo->id}}">
                          <td style="font-size: 11px; font-family: 'Khmer OS System'; text-align: center;">{{$no++}}</td>
                          <td style="font-size: 11px; font-family: 'Khmer OS System';">
                              {{$tmppo->product->name}}
                          </td>
                          <td style="font-size: 11px; font-family: 'Khmer OS System'; text-align: center;">
                            {{$tmppo->qty}}
                          </td>
                          <td width="150px" style="text-align: center;">
                          <button style="padding: 2px;" class="btn btn-danger fa fa-remove btn-xs" type="button" onclick="removeRequestpro({{$tmppo->id}})"> Remove</button></td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
      @else
            <h4 style="margin-left: 5px;">No found results</h4>
      @endif

