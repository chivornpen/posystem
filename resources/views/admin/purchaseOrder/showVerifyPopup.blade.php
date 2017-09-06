                  <div class="row">
                      <div class="col-lg-3">
                           <div class="form-group {{ $errors->has('customerid') ? ' has-error' : '' }}">
                            {!!Form::label('customerid','Customer Number :',[])!!}
                           <input type="id" name="id" class="form-control" disabled="true" value="<?php echo "CAM-CUS-" . sprintf('%06d',$customerid);?>">
                            @if ($errors->has('customerid'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('customerid') }}</strong>
                                </span>
                            @endif
                          </div>
                        </div>
                        <div class="col-lg-3">
                             <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                              {!!Form::label('name','Name :',[])!!}
                              {!!Form::text('name',$cusname,['class'=>'form-control','disabled'=>'true'])!!}
                              @if ($errors->has('name'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('name') }}</strong>
                                  </span>
                              @endif
                            </div>
                          </div>
                          <div class="col-lg-2">
                             <div class="form-group {{ $errors->has('contactNo') ? ' has-error' : '' }}">
                              {!!Form::label('contactNo','ContactNo :',[])!!}
                              {!!Form::text('contactNo',$phone,['class'=>'form-control','disabled'=>'true'])!!}
                              @if ($errors->has('contactNo'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('contactNo') }}</strong>
                                  </span>
                              @endif
                            </div>
                          </div>
                          <div class="col-lg-2">
                             <div class="form-group {{ $errors->has('contactNo') ? ' has-error' : '' }}">
                              {!!Form::label('contactNo','Channel :',[])!!}
                              {!!Form::text('contactNo',$channel,['class'=>'form-control','disabled'=>'true'])!!}
                              @if ($errors->has('contactNo'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('contactNo') }}</strong>
                                  </span>
                              @endif
                            </div>
                          </div>
                          <div class="col-lg-2">
                             <div class="form-group {{ $errors->has('contactNo') ? ' has-error' : '' }}">
                              {!!Form::label('contactNo','Edit PO By :',[])!!}
                              {!!Form::text('contactNo',$username,['class'=>'form-control','disabled'=>'true'])!!}
                              @if ($errors->has('contactNo'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('contactNo') }}</strong>
                                  </span>
                              @endif
                            </div>
                          </div>
                        </div>
   <div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default table-responsive">
            <table class="table table-responsive table-bordered table-striped" cellspacing="0">
                  <thead>
                      <tr>
                          <th>No</th>
                          <th>Product Name</th>
                          <th>Quantity</th>
                          <th>UnitPrice</th>
                          <th>Amount</th>
                          <th>Status</th>
                      </tr>
                  </thead>
                  <?php $no=1;?>
                  <tbody>
                      @foreach($pos as $po)
                      <tr>
                          <td style="text-align: center;">{{$no++}}</td>
                          <td style="font-size: 11px; font-family: 'Khmer OS System';">
                            {{$po->product->name}}
                          </td>
                          <td style="font-size: 11px; font-family: 'Khmer OS System';text-align: center;">
                            {{$po->qty}}
                          </td>
                          <td style="font-size: 11px; font-family: 'Khmer OS System';text-align: center;">
                            <?php 
                                echo "$ " . number_format($po->unitPrice,2);
                            ?>
                          </td>
                          <td width="150px" style="font-size: 11px; font-family: 'Khmer OS System'; text-align: center;">
                            <?php 
                                echo "$ " . number_format($po->amount,2);
                            ?>
                          </td>
                          <td width="150px" style="font-size: 11px; font-family: 'Khmer OS System'; text-align: center;">
                            <?php 
                                $status = $po->recordStatus;
                               if($status=='n'){
                                  echo "No Change";
                               }elseif($status=='a'){
                                  echo "Add New";
                               }elseif ($status=='e') {
                                  echo "Edit";
                               }else{
                                  echo "Remove";
                               }
                            ?>
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