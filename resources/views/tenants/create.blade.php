@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <h1>{{$title}}</h1>

                {!! Form::open(['route' => 'tenants.store','class' => 'form-horizontal']) !!}
                
                {!! Form::hidden('lease_id','') !!}
                
                {!! Form::hidden('type','Tenant') !!}
            
                <div class="row">
                
                    <div class="col-sm-12">
                        <div class="form-group @if($errors->first('firstname')) has-error @endif">
                            {!! Form::label('firstname','First Name',['for' => 'firstname','class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-5">
                                    {!! Form::text('firstname',null,['id' => 'firstname','class' => 'form-control']) !!}
                                <small class="text-danger">{{ $errors->first('firstname') }}</small>
                            </div>
                        </div>
                        <div class="form-group @if($errors->first('lastname')) has-error @endif">
                            {!! Form::label('lastname','Last Name',['for' => 'lastname','class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-5">
                                    {!! Form::text('lastname',null,['id' => 'lastname','class' => 'form-control']) !!}
                                <small class="text-danger">{{ $errors->first('lastname') }}</small>
                            </div>
                        </div>
                        <div class="form-group @if($errors->first('email')) has-error @endif">
                            {!! Form::label('email','Email:',['for' => 'email','class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-5">
                                    {!! Form::text('email',null,['id' => 'email','class' => 'form-control']) !!}
                                <small class="text-danger">{{ $errors->first('email') }}</small>
                            </div>
                        </div>
                        <div class="form-group @if($errors->first('phone')) has-error @endif">
                            {!! Form::label('phone','Phone:',['for' => 'phone','class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-5">
                                    {!! Form::text('phone',null,['id' => 'phone','class' => 'form-control']) !!}
                                <small class="text-danger">{{ $errors->first('phone') }}</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-5 col-md-offset-2">
                                <button type="submit" id="add" class="btn btn-success btn-lg pull-right control-label">Create <i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
                
            </div>
        </div>
        <!-- /.row -->
@stop