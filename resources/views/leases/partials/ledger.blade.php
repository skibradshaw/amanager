<div class="panel @if($lease->rentBalance()>0) panel-danger @else panel-default @endif">
    <div class="panel-heading">
        @if($lease->rentBalance() > 0)
        <h4><span class="text-danger pull-right">Rent Balance: {{$lease->rent_balance_in_dollars}}</span></h4>
        @else
        <h4><span class="text-success pull-right">Rent Balance: {{$lease->rent_balance_in_dollars}}</span></h4>
        @endif
        <i class="fa fa-bar-chart-o fa-fw"></i> Ledger
    </div>
    <div class="panel-body">
	  <div class="table-responsive">
		  <table id="ledger" class="table table-condensed  table-hover responsive ledger">
			<thead>
				<tr>
				  <th>Rent & Fees</th>
				  @foreach($lease->details as $m)
				  	<th nowrap="" align="center" class="text-center"> {{ $m->name }} </th>
				  @endforeach
				</tr>
			</thead>	
			<tbody>
	            <tr>
		            <td>Rent</td>
					@foreach($lease->details as $m)									
		                <td align="right" class="text-right" nowrap>{{ $m->monthly_rent_in_dollars }} </td>
		            @endforeach
	            </tr>					            
				<tr>
				    <td>Pet Rent</td>
					@foreach($lease->details as $m)									
					    <td align="right" class="text-right edit" id="{{$m->id}}" nowrap><a href="{{ route('leases.pet.show',[$property, $apartment, $lease]) }}" data-toggle="modal" data-target="#largeModal">{{ $m->monthly_pet_rent_in_dollars }}</a></td>
					@endforeach

				</tr>
				<tr>
					<td>Fees</td>
					@foreach($lease->details as $m)									
						<td align="right" class="text-right" nowrap><a href="{{ route('fees.create',[$property, $apartment, $lease,'month='.$m->month, 'year='.$m->year])}} "  data-toggle="modal" data-target="#largeModal"> {{ money_format('%.2n',$lease->monthFees($m->month,$m->year)/100) }}</a></td>
					@endforeach
				</tr>		
				<tr>
						<td> &nbsp; </td>
						<td colspan="{{$lease->details->count()}}" class="text-right"></td>

				</tr>
				<tr>
					<th>Payments</th>
					<th colspan="{{$lease->details->count()}}" class="text-right"><small>**Payments made outside of lease dates are placed in first month.</small> </th>
				</tr>
				@foreach($lease->tenants as $t)
					<tr>
						<td> {{ $t->lastname }} </td>

						@foreach($lease->details as $m)
							<td align="right" class="text-right" nowrap>
								@if(count($m->getRentPayments($t->id)) > 1)
								<span data-toggle="tooltip" data-html="true" data-placement="top" data-content="Test" 
								title="@foreach($m->getRentPayments($t->id) as $p) {{$p->paid_date->format('n/j/y')}}: {{money_format('%.2n',$p->amount/100)}}<br>@endforeach">
								@else
								<span>
								@endif
								{{money_format('%.2n',$m->rentPayments($t->id)/100)}}
								</span>
							</td>
						@endforeach
					</tr>
				@endforeach
				<tr>
						<td> &nbsp; </td>
						<td colspan="{{$lease->details->count()}}" class="text-right">&nbsp; </td>

				</tr>			            
			</tbody>
			<tfoot>
				<tr>
					<td><strong>Total Rent:</strong></td>
					@foreach($lease->details as $m)
						<td align="right" class="text-right" nowrap><strong> <span id="balance{{$m->id}}">{{ $m->monthly_due_in_dollars }}</span></strong></td>
					@endforeach
				</tr>
				<tr>
					<td><strong>Total Payments:</strong></td>
					@foreach($lease->details as $m)
						<td align="right" class="text-right" nowrap><strong> <span id="balance{{$m->id}}">{{ $m->monthly_payments_in_dollars }}</span></strong></td>
					@endforeach
				</tr>
				<tr>
					
				</tr>
				<tr>
					<td><strong>Balance:</strong></td>
					@foreach($lease->details as $m)
						<td align="right" class="text-right" nowrap><strong> <span id="balance{{$m->id}}">{{ $m->month_balance_in_dollars }}</span></strong></td>
					@endforeach
				</tr>
			</tfoot>	
		  </table>
		  
	  </div>        
    </div>
</div>
