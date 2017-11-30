@extends('layouts.admin')
@section('content')
<div>
  @include('nav.message')
</div>
<div class="container-fluid"><br>
  <div class="panel panel-default">
    <div class="panel-heading">
      PAYMENTS FORM
    </div>
      <div class="panel panel-body">
        <div class="container-fluid table-responsive">
          {!! Form::open(['method'=>'post', 'action'=>'InvoicePOController@submit'])!!}
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group {{ $errors->has('invN') ? ' has-error' : '' }}">
                  {!!Form::label('invN','Invoice Number : ',[])!!}
                  <select required name="invN" id="invN" class="form-control" onchange="InvoiceView()">
                    <option value="">Please select</option>
                    @foreach( $purchaseorders as $invN)
                    <option value="{{$invN->id}}">{!! "CAM-IN-" . sprintf('%06d',$invN->id) !!}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('invN'))
                      <span class="help-block">
                        <strong>{{ $errors->first('invN') }}</strong>
                      </span>
                    @endif
                </div>
              </div>
            </div>
            <div hidden id="textbox">
              <div class="row">
              <div class="col-lg-6">
                <div class="form-group {{ $errors->has('cradit') ? ' has-error' : '' }}">
                  {!!Form::label('cradit','Credit : ',[])!!}
                  <div class="input-group">
                  {!!Form::text('cradit',null,['class'=>'form-control cradit','readonly'=>'readonly'])!!}
                    <span class="input-group-btn">
                      <a href="#" class="btn btn-warning" onclick="paidAll()"><i class="fa fa-caret-right" aria-hidden="true"></i> </a>
                    </span>
                  </div>
                    @if ($errors->has('cradit'))
                      <span class="help-block">
                        <strong>{{ $errors->first('cradit') }}</strong>
                      </span>
                    @endif
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group {{ $errors->has('paid') ? ' has-error' : '' }}">
                  {!!Form::label('paid',' Paid  ',[])!!}
                  {!!Form::text('paid',null,['class'=>'form-control paids','required'=>'true','autocomplete'=>'off'])!!}
                  @if ($errors->has('paid'))
                    <span class="help-block">
                      <strong>{{ $errors->first('paid') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
            </div>
          </div>
            <div hidden class="row currency">
              <div class="col-lg-4">
                  <div class="form-group {{ $errors->has('cur') ? ' has-error' : '' }}">
                    {!!Form::label('cur','Gender : ',[])!!}
                    {!!Form::select('cur', ['KHR' => 'KHR', 'BATH' => 'BATH'], null, ['class'=>'form-control','id'=>'curren','required'=>'true','placeholder'=> '--Please select--'])!!}
                    @if ($errors->has('cur'))
                        <span class="help-block">
                            <strong>{{ $errors->first('cur') }}</strong>
                        </span>
                    @endif
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                   {!!Form::label('exchangerate','Exchange Rate : ',[])!!}
                    {!!Form::number('exchangerate',null,['class'=>'form-control exchangerate','required'=>'true','readonly'=>'readonly','min'=>0,'autocomplete'=>'off'])!!}
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                   {!!Form::label('postAmount','Post Amount : ',[])!!}
                  {!!Form::text('postAmount',null,['class'=>'form-control postAmount','readonly'=>'readonly'])!!}
                </div>
              </div>
            </div>
                <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-sm btn-success submit"> Submit </button>
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger"> Discard </a>
                  </div>
                </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>

@stop
@section('script')
<script type="text/javascript">
  $(document).ready(function() {
         $('.example').DataTable({
            responsive: true
        });
    });
        function InvoiceView() {
          $('.paids').val('');
          $('#currency').val(0);
          $('.exchangerate').val('');
          $('.postAmount').val('');
          var InvId = $('#invN').val();
            if(InvId!=''){
              $("#textbox").fadeIn('slow');
                $.ajax({
                    type:'get',
                    url:"{{url('/entryPayment')}}"+"/"+InvId,
                    dataType: 'json',
                    success:function (response) {
                        $('.cradit').val(response);

                    },
                    error:function (error) {
                        console.log(error);
                    }
                });
            }else{
              $('.cradit').val('');
              $('.paids').val('');
              $("#textbox").fadeOut('slow');
              $('.currency').fadeOut('slow');
            }
        }

  function paidAll() {
        var payall = $('.cradit').val();
        $('.paids').val(payall);
        $(".currency").fadeIn('slow');
    }

    $( ".paids" ).keyup(function() {
      var paids =0;
      var paid = $(this).val();
      var cradit = $('.cradit').val();
      paids = parseFloat(paid);
      var cradits = parseFloat(cradit);
      if(paids>cradits || paids<0)
      {
        $('.paids').css('border','1px solid red');
        $('.submit').attr('disabled','true');
        $('.postAmount').val(0);
      }else{
        $('.paids').css('border','1px solid lightblue');
        $('.submit').removeAttr('disabled','true');
        var rate = $('.exchangerate').val();
        $(".currency").fadeIn('slow');
        var total = paids * rate;
        var postAmount = total.toFixed();
        $('.postAmount').val(postAmount);
      }   
    });
      $('#curren').on('change',function(e){
        var currency = $('#curren').val()
        $('.exchangerate').removeAttr('readonly','true');
        $('.exchangerate').focus();
        $('.exchangerate').val('');
        if(currency==null){
          $('.exchangerate').attr('readonly','true');
          $('.exchangerate').val('');
          $('#curren').focus();
        }
  });
    $( ".exchangerate" ).keyup(function() {
      var rate = $(this).val();
      var paid = $('.paids').val();
      if(rate<0){
        $('.exchangerate').css('border','1px solid red');
        $('.submit').attr('disabled','true');
      }else{
        $('.exchangerate').css('border','1px solid lightblue');
        $('.submit').removeAttr('disabled','true');
        var total = paid * rate;
        var postAmount = total.toFixed();
        $('.postAmount').val(postAmount);
      }
      
    });
  </script>
@stop