@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <h1>Edit Lease: {{$property->name}} {{$apartment->name}} <small>Lease: {{$lease->start->format('n/j/Y')}} - {{$lease->end->format('n/j/Y')}}</small></h1>
                <div class="row"> 
                    @if(isset($lease))
                        {!! Form::model($lease,['route' => ['leases.update',$property, $apartment, $lease],'method' => 'PUT','class' => 'form-horizontal']) !!}
                    @else
                        {!! Form::open(['route' => ['leases.store',$property, $apartment],'class' => 'form-horizontal']) !!}
                    @endif
                    {!! Form::hidden('apartment_id',$apartment->id) !!}
                    <div class="form-group @if($errors->first('start')) has-error @endif">
                        {!! Form::label('start','Lease Start',['for' => 'start', 'class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            {!! Form::text('start',$lease->start->format('n/j/Y'),['id' => 'start','class' => 'datepicker form-control','placeholder' => 'mm/dd/yyyy','style' => 'position: relative; z-index: 100000;']) !!}
                            <small class="text-danger">{{ $errors->first('start') }}</small>
                        </div>
                    </div>
                    <div class="form-group @if($errors->first('end')) has-error @endif">
                        {!! Form::label('end','Lease End',['for' => 'end', 'class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            {!! Form::text('end',$lease->end->format('n/j/Y'),['id' => 'end','class' => 'datepicker form-control','placeholder' => 'mm/dd/yyyy','style' => 'position: relative; z-index: 100000;']) !!}
                            <small class="text-danger">{{ $errors->first('end') }}</small>
                        </div>
                    </div>             
                    <div class="form-group @if($errors->first('monthly_rent')) has-error @endif">
                        {!! Form::label('monthly_rent','Monthly Rent',['for' => 'monthly_rent', 'class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('monthly_rent',number_format($lease->monthly_rent/100,2, '.', ''),['id' => 'monthly_rent','class' => 'form-control']) !!}
                            </div>
                            <small class="text-danger">{{ $errors->first('monthly_rent') }}</small>
                        </div>
                    </div>
                    <div class="form-group @if($errors->first('pet_rent')) has-error @endif">
                        {!! Form::label('pet_rent','Pet Rent',['for' => 'pet_rent', 'class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('pet_rent',number_format($lease->pet_rent/100,2, '.', ''),['id' => 'pet_rent','class' => 'form-control']) !!}
                            </div>
                            <small class="text-danger">{{ $errors->first('pet_rent') }}</small>
                        </div>
                    </div>
                    <div class="form-group @if($errors->first('deposit')) has-error @endif">
                        {!! Form::label('deposit','Security Deposit',['for' => 'deposit', 'class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('deposit',number_format($lease->deposit/100,2, '.', ''),['id' => 'deposit','class' => 'form-control']) !!}
                            </div>
                            <small class="text-danger">{{ $errors->first('deposit') }}</small>
                        </div>
                    </div>
                    <div class="form-group @if($errors->first('pet_deposit')) has-error @endif">
                        {!! Form::label('pet_deposit','Pet Deposit',['for' => 'pet_deposit', 'class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('pet_deposit',number_format($lease->pet_deposit/100,2, '.', ''),['id' => 'pet_deposit','class' => 'form-control']) !!}
                            </div>
                            <small class="text-danger">{{ $errors->first('pet_deposit') }}</small>
                        </div>
                    </div>
                    <div class="form-group">
                         <div class="col-md-offset-2 col-sm-3">
                            @if(isset($lease))
                            <button type="submit" class="btn btn-success btn-lg pull-right control-label">Update</button>

                            @else
                            <button type="submit" class="btn btn-success btn-lg pull-right control-label">Save</button>
                            @endif                            
                         </div>
                     </div>
                              
                    
                    {!! Form::close() !!}
                </div>
                <div class="row"><hr></div>

                
            </div>
        </div>
        <!-- /.row -->
@stop
@section('scripts')
<script>
  
    $( "#start" ).datepicker({
        dateFormat: "m/d/yy",
    });
    $( "#end" ).datepicker({
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