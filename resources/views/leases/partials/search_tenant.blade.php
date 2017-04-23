	 <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title" id="myModalLabel">
		Add Tenant <strong>{{$apartment->name}}</strong> <small>Lease: {{$lease->start->format('n/j/Y')}} - {{$lease->end->format('n/j/Y')}}</small>
		</h4>
	</div>
	{!! Form::open(['route' => ['leases.add_tenant',$property,$apartment,$lease],'class' => 'form-horizontal']) !!}
	<div class="modal-body">
		<div class="row">
		
			<div class="col-sm-12">
			 
				 <div class="form-group">
					{!! Form::label('name','Search Tenant Name: ') !!}
					<input id="q" name="name" autofocus type="text" size="50px">&nbsp;OR&nbsp;<a href="/tenants/create?lease_id={{ $lease->id }}" class="btn btn-primary btn-sm" data-dismiss="modal">Create a New Tenant</a>

					{!! Form::hidden('tenant_id',null,['id' => 'tenant_id']) !!}
					{!! Form::hidden('lease_id',$lease->id) !!}
					 
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">

	
	<button type="submit" id="add" class="btn btn-default">Add to Lease <i class="fa fa-plus-right"></i></button>

	</div>
	{!! Form::close() !!}