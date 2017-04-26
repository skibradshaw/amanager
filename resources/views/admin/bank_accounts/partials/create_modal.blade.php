	 <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title" id="myModalLabel">
		{{$title}}
		</h4>
	</div>
  @if(isset($bank))
	{!! Form::model($bank,['route' => ['bank_accounts.update',$bank],'method' => 'PUT','class' => 'form-horizontal']) !!}
  @else
	{!! Form::open(['route' => ['bank_accounts.store'],'method' => 'POST','class' =>'form-horizontal']) !!}
  @endif


	<div class="modal-body">
		<div class="row">
		
			<div class="col-md-7 col-md-offset-2">
				<div class="form-group @if($errors->first('name')) has-error @endif">
                    {!! Form::label('name','Name:',['id' => 'name','class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-9">
                            {!! Form::text('name',null,['id' => 'name','class' => 'form-control']) !!}
                        <small class="text-danger">{{ $errors->first('name') }}</small>
                    </div>
                </div>
				<div class="form-group @if($errors->first('property_id')) has-error @endif">
                    {!! Form::label('property_id','Property:',['id' => 'property_id','class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-9">
                            {!! Form::select('property_id',$navProperties->pluck('name','id'),null,['id' => 'property_id','class' => 'form-control']) !!}
                        <small class="text-danger">{{ $errors->first('property_id') }}</small>
                    </div>
                </div>				
			</div>
		</div>
	</div>
	<div class="modal-footer">

	<button type="button" class="btn btn-default" data-dismiss="modal">Nevermind</button>
	<button type="submit" id="add" class="btn btn-default">Save<i class="fa fa-plus-right"></i></button>

	</div>
	{!! Form::close() !!}