	 <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title" id="myModalLabel">
		Manage Pet Rent <strong>{{$apartment->name}}</strong> <small>Lease: {{$lease->start->format('n/j/Y')}} - {{$lease->end->format('n/j/Y')}}</small>
		</h4>
	</div>
{!! Form::open(['route' => ['leases.pet.store',$property,$apartment,$lease],'method' => 'post','id' => 'petrent','class' => 'form-horizontal']) !!}
	<div class="modal-body">
		<div class="row">	
			<div class="col-sm-12">
                <div class="form-group @if($errors->first('pet_deposit')) has-error @endif">
                    {!! Form::label('pet_deposit','Pet Deposit:',['for' => 'pet_deposit','class' => 'col-sm-4 control-label']) !!}
                    <div class="col-sm-3">
		            <div class="input-group">
		                <span class="input-group-addon">$</span>
		                {!! Form::text('pet_deposit',number_format($lease->pet_deposit/100,2),['id' => 'pet_deposit','class' => 'form-control']) !!}
		            </div>

                            
                        <small class="text-danger">{{ $errors->first('pet_deposit') }}</small>
                    </div>
                </div>
                <div class="form-group @if($errors->first('pet_rent')) has-error @endif">
                    {!! Form::label('pet_rent','Set Monthly Pet Rent:',['for' => 'pet_rent','class' => 'col-sm-4 control-label']) !!}
                    <div class="col-sm-3">
		            <div class="input-group">
		                <span class="input-group-addon">$</span>
		                {!! Form::text('pet_rent',number_format($lease->pet_rent/100,2),['id' => 'month_pet_rent','class' => 'form-control']) !!}
		            </div>
                            
                        <small class="text-danger">{{ $errors->first('month_pet_rent') }}</small>
                    </div>
                    <div class="col-sm-4">
                    	<a href="#" class="btn btn-success" id="applyall">Apply to All Months</a>
                    </div>
                </div>
                <div class="form-group"><hr></div> 
                @foreach($lease->details as $d)
                <div class="form-group @if($errors->first($d->id)) has-error @endif">
                    {!! Form::label('monthly_pet_rent','Month Ending '.$d->name . ':',['class' => 'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
			            <div class="input-group">
			                <span class="input-group-addon">$</span>
			                 {!! Form::text('monthly_pet_rent['.$d->id.']',str_replace('$','',$d->monthly_pet_rent_in_dollars),['class' => 'month_pet_rent', 'id' => $d->name]) !!}
			            </div>
                        <small class="text-danger">{{ $errors->first($d->id) }}</small>
                    </div>
                </div>               
                @endforeach	


			</div>
		</div>
	</div>

	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Nevermind</button>
		<button type="submit" id='submit_button' class="btn btn-primary">Apply</button>		
	</div>

{!! Form::close(['class' => 'close-reveal-modal']) !!} 


<script type="text/javascript">
$( document ).ready(function() {
	$('#applyall').click(function(){
		var amt = Number($('#month_pet_rent').val());
		// alert(amt.toFixed(2));
		$('.month_pet_rent').val(amt.toFixed(2));
	}); 


});	
</script>