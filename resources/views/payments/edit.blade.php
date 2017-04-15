@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <h1>{{$title}} <small>Lease: {{$lease->start->format('n/j/y')}} - {{$lease->end->format('n/j/y')}}</small> </h1>
                <p></p>
                <div class="row">
                  <div class="col-lg-7">
                      @if(isset($payment))
                      {!! Form::model($payment,['route' => ['payments.update',$property,$apartment,$lease,$payment],'method' => 'PUT','class' => 'form-horizontal']) !!}
                      @else
                      {!! Form::open(['route' => ['payments.store',$property,$apartment,$lease],'class' => 'form-horizontal']) !!}
                      @endif

                      @include('payments.partials.edit')
                      <div class="form-group">
                           <div class="col-md-offset-4 col-sm-6">
                              @if(isset($payment))
                              <button type="submit" class="btn btn-success btn-lg pull-right control-label">Update</button>

                              @else
                              <button type="submit" class="btn btn-success btn-lg pull-right control-label">Save</button>
                              @endif                            
                           </div>
                       </div>                
                      {!! Form::close() !!}
                  </div>                  
                </div> 
            </div>

        </div>
        <div class="row">
          <div class="col-lg-12"><hr></div>
        </div>
        <div class="row">
          <div class="col-lg-12">
              @include('leases.partials.ledger')           
          </div>
        </div>
        <!-- /.row -->
        <div class="row">
             <div class="col-lg-6">
                @include('leases.partials.payments_panel')
            </div>            
             <div class="col-lg-6">
                 @include('leases.partials.deposit_panel')
            </div> 
        </div>
        <!-- /.row -->

        <!-- /.row -->
@stop
@section('scripts')
<script>
    $( "#paid_date" ).datepicker();

$('input,textarea').focus(function(){
   $(this).data('placeholder',$(this).attr('placeholder'))
          .attr('placeholder','');
}).blur(function(){
   $(this).attr('placeholder',$(this).data('placeholder'));
});    
</script>
@stop