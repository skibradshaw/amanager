	 <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title" id="myModalLabel">
		Add Tenant <strong>{{$apartment->name}}</strong> <small>Lease: {{$lease->start->format('n/j/Y')}} - {{$lease->end->format('n/j/Y')}}</small>
		</h4>
	</div>
		{!! Form::open(['route' => 'tenants.store','class' => 'form-horizontal']) !!}
		@if($lease->id > 0)
		{!! Form::hidden('lease_id',$lease->id) !!}
		@endif
		{!! Form::hidden('type','tenant') !!}
	<div class="modal-body">
		<div class="row">
		
			<div class="col-sm-12">
                <div class="form-group @if($errors->first('firstname')) has-error @endif">
                    {!! Form::label('firstname','First Name',['id' => 'firstname','class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-8">
                            {!! Form::text('firstname',null,['id' => 'firstname','class' => 'form-control']) !!}
                        <small class="text-danger">{{ $errors->first('firstname') }}</small>
                    </div>
                </div>
				<div class="form-group @if($errors->first('lastname')) has-error @endif">
                    {!! Form::label('lastname','Last Name',['id' => 'lastname','class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-8">
                            {!! Form::text('lastname',null,['id' => 'lastname','class' => 'form-control']) !!}
                        <small class="text-danger">{{ $errors->first('lastname') }}</small>
                    </div>
                </div>
				<div class="form-group @if($errors->first('email')) has-error @endif">
                    {!! Form::label('email','Email:',['id' => 'email','class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-8">
                            {!! Form::text('email',null,['id' => 'email','class' => 'form-control']) !!}
                        <small class="text-danger">{{ $errors->first('email') }}</small>
                    </div>
                </div>
				<div class="form-group @if($errors->first('phone')) has-error @endif">
                    {!! Form::label('phone','Phone:',['id' => 'phone','class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-8">
                            {!! Form::text('phone',null,['id' => 'phone','class' => 'form-control']) !!}
                        <small class="text-danger">{{ $errors->first('phone') }}</small>
                    </div>
                </div>
			</div>
		</div>
	</div>
	<div class="modal-footer">

	<button type="button" class="btn btn-default" data-dismiss="modal">Nevermind</button>
	<button type="submit" id="add" class="btn btn-default">Add to Lease <i class="fa fa-plus-right"></i></button>

	</div>
	{!! Form::close() !!}