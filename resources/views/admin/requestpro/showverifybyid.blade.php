   <div class="row">
            <div class="col-lg-12">
              <h5 style="margin-left: 4px;">Request Date : {{$reqDate}}</h5> 
              <h5 style="margin-left: 4px;">Request By : {{$username}}</h5>   
              <h5 style="margin-left: 4px;">Description : {{$status}}</h5>
            </div>
          </div>
   <div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default table-responsive">
            <table class="table table-responsive table-bordered table-striped" cellspacing="0">
                  <thead>
                      <tr>
                          <th style="text-align: center;">No</th>
                          <th style="text-align: center;">Product Name</th>
                          <th style="text-align: center;">Quantity</th>
                      </tr>
                  </thead>
                  <?php $no=1;?>
                  <tbody>
                      @foreach($details->products as $detail)
                      <tr>
                          <td style="text-align: center;">{{$no++}}</td>
                          <td style="font-size: 11px; font-family: 'Khmer OS System';">
                            {{$detail->name}}
                          </td>
                          <td style="font-size: 11px; font-family: 'Khmer OS System';text-align: center;">
                            {{$detail->pivot->qty}}
                          </td>
                      </tr>
                      @endforeach
                      <script type="text/javascript">

                      RemoveSpace();
                      function RemoveSpace(){
                    
                              var el = document.querySelector('.panel-default');
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