@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <h1>Create a New Lease for {{$property->name}} {{$apartment->name}}</h1>
                <div class="row"> 
                    @if(isset($lease))
                        {!! Form::model($lease,['route' => ['leases.update',$property, $apartment, $lease],'method' => 'update','class' => 'form-horizontal']) !!}
                    @else
                        {!! Form::open(['route' => ['leases.store',$property, $apartment],'class' => 'form-horizontal']) !!}
                    @endif
                    {!! Form::hidden('apartment_id',$apartment->id) !!}
                    <div class="form-group @if($errors->first('start')) has-error @endif">
                        {!! Form::label('start','Lease Start',['for' => 'start', 'class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            {!! Form::text('start',null,['id' => 'start','class' => 'datepicker form-control','placeholder' => 'mm/dd/yyyy','style' => 'position: relative; z-index: 100000;']) !!}
                            <small class="text-danger">{{ $errors->first('start') }}</small>
                        </div>
                    </div>
                    <div class="form-group @if($errors->first('end')) has-error @endif">
                        {!! Form::label('end','Lease End',['for' => 'end', 'class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            {!! Form::text('end',null,['id' => 'end','class' => 'datepicker form-control','placeholder' => 'mm/dd/yyyy','style' => 'position: relative; z-index: 100000;']) !!}
                            <small class="text-danger">{{ $errors->first('end') }}</small>
                        </div>
                    </div>             
                    <div class="form-group @if($errors->first('monthly_rent')) has-error @endif">
                        {!! Form::label('monthly_rent','Monthly Rent',['for' => 'monthly_rent', 'class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('monthly_rent',null,['id' => 'monthly_rent','class' => 'form-control']) !!}
                            </div>
                            <small class="text-danger">{{ $errors->first('monthly_rent') }}</small>
                        </div>
                    </div>
                    <div class="form-group @if($errors->first('pet_rent')) has-error @endif">
                        {!! Form::label('pet_rent','Pet Rent',['for' => 'pet_rent', 'class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('pet_rent',null,['id' => 'pet_rent','class' => 'form-control']) !!}
                            </div>
                            <small class="text-danger">{{ $errors->first('pet_rent') }}</small>
                        </div>
                    </div>
                    <div class="form-group @if($errors->first('deposit')) has-error @endif">
                        {!! Form::label('deposit','Security Deposit',['for' => 'deposit', 'class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('deposit',null,['id' => 'deposit','class' => 'form-control']) !!}
                            </div>
                            <small class="text-danger">{{ $errors->first('deposit') }}</small>
                        </div>
                    </div>
                    <div class="form-group @if($errors->first('pet_deposit')) has-error @endif">
                        {!! Form::label('pet_deposit','Pet Deposit',['for' => 'pet_deposit', 'class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                {!! Form::text('pet_deposit',null,['id' => 'pet_deposit','class' => 'form-control']) !!}
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
        dateFormat: "mm/dd/yy",
        onSelect: function(dateText, instance) {
            date = $.datepicker.parseDate(instance.settings.dateFormat, dateText, instance.settings);
            date.setMonth(date.getMonth() + 12);
            date.setDate(date.getDate() - 1);
            $("#end").datepicker("setDate", date).attr('placeholder','');
        }
    });
    $( "#end" ).datepicker();

$('input,textarea').focus(function(){
   $(this).data('placeholder',$(this).attr('placeholder'))
          .attr('placeholder','');
}).blur(function(){
   $(this).attr('placeholder',$(this).data('placeholder'));
});

</script>
@stop