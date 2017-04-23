@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <h1>{{$title}}</h1>
                <div class="row">
                  <div class="col-lg-8">
                      @if(isset($fee))
                      {!! Form::model($fee,['route' => ['fees.update',$property,$apartment,$lease,$fee],'method' => 'PUT','class' => 'form-horizontal']) !!}
                      @else
                      {!! Form::open(['route' => ['fees.store',$property,$apartment,$lease],'class' => 'form-horizontal']) !!}
                      @endif

                      @include('fees.partials.edit')
                      <div class="form-group">
                           <div class="col-md-offset-4 col-sm-6">
                              @if(isset($fee))
                              <button type="submit" class="btn btn-success btn-lg pull-right control-label">Update</button>

                              @else
                              <button type="submit" class="btn btn-success btn-lg pull-right control-label">Save</button>
                              @endif                            
                           </div>
                       </div>                
                      {!! Form::close() !!}
                  </div>                

                
                  <div class="col-lg-4">
                    @include('leases.partials.payments_panel')
                    @include('leases.partials.deposit_panel')
                  </div>  
                </div> 


                
            </div>
        </div>
        <!-- /.row -->
@stop
@section('scripts')
<script>
    $( "#due_date" ).datepicker({
      dateFormat: "m/d/yy",
    });

$('input,textarea').focus(function(){
   $(this).data('placeholder',$(this).attr('placeholder'))
          .attr('placeholder','');
}).blur(function(){
   $(this).attr('placeholder',$(this).data('placeholder'));
});    
</script>
@stop