@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-5"> <h1>{{$title or ''}}</h1></div>
                    <div class="col-md-3 col-md-offset-4 text-right">
                        {!! Form::select('property_id',$navProperties->pluck('name','id')->prepend('Show All Properties',0), (!is_null($property)) ? $property->id : null,['id' => 'property_id','class' => 'form-control pull-right']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped table-condensed  table-hover responsive" id="tenants" width="100%">
                        <thead>
                        <tr>
                            <th align="center" style="cursor:pointer" class="text-center">Number</th>
                            <th align="center" style="cursor:pointer">Name</th>
                            <th align="center" style="cursor:pointer" class="text-center">Phone</th>
                            <th align="center" style="cursor:pointer" class="text-center">Email</th>
                            <th align="center" style="cursor:pointer" class="text-center">Apartment</th>
                            <th align="center" style="cursor:pointer" class="text-center">Lease</th>
                            <th align="center" style="cursor:pointer" class="text-center">Remarks</th>
                            <th align="center" style="cursor:pointer" class="text-center">Rent Balance</th>
                            <th align="center" style="cursor:pointer" class="text-center">Security Deposit Balance</th>
                        </tr>
                        </thead>
                        <tbody>

                                @forelse($tenants as $t)
                                    <tr>
                                            <td>{{ $t->active_lease->apartment->number}}</td>
                                            <td nowrap="nowrap">
                                                <a href="{{route('tenants.edit',[$t])}}">{{$t->lastname}}. {{$t->firstname}}</a>
                                            </td>
                                            <td  nowrap="nowrap">
                                                {{$t->phone}}
                                            </td>
                                            <td>
                                                @if(empty($t->email))
                                                <a href="{{route('tenants.edit',[$t])}}" class="btn btn-danger btn-xs btn-block">Add Email</a> 
                                                @else 
                                                {{$t->email}}
                                                @endif
                                            </td>
                                            <td class="text-center"><a href="{{route('leases.show',[$t->active_lease->apartment->property,$t->active_lease->apartment,$t->active_lease])}}">{{ $t->active_lease->apartment->property->name }} {{$t->active_lease->apartment->name}}</a></td>
                                            <td>
                                                {{$t->active_lease->start->format('n/j/Y')}} - {{$t->active_lease->end->format('n/j/Y')}}
                                            </td>
                                            <td class="text-center">
                                                @if($t->active_lease->pet_rent > 0)
                                                <i class="fa fa-paw fa-fw" data-toggle="tooltip" title="Pets: {{money_format('%.2n',$t->active_lease->pet_rent/100)}}/mo"></i>
                                                @endif
                                                @if($t->active_lease->fees->sum('amount') > 0)
                                                <i class="fa fa-usd fa-fw" data-toggle="tooltip" title="Total Fees: {{money_format('%.2n',$t->active_lease->fees->sum('amount')/100)}}"></i>
                                                @endif                                            
                                            </td>
                                            <td align="right" class="text-center">{{$t->active_lease->rent_balance_in_dollars}}</td>
                                            <td align="right" class="text-center">{{$t->active_lease->deposit_balance_in_dollars}}</td>
                                    </tr>       
                                @empty
                                @endforelse

                        </tbody>
                        </table>
                    </div>
                </div>
                    
                <hr>                  


                
            </div>
        </div>
        <!-- /.row -->
@stop

@section('scripts')
<script>


    /** DATATABLES  */
    $('#tenants').DataTable({
       paging: false,
       searching: true,
       aaSorting: [[1, 'asc']],
       columnDefs: [  
        { targets: [5,6], aaSorting: false},
        { 'orderData':[0], 'targets': [4] },
        {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }
        // { targets: '_all', visible: false }
        ]
    });    
    $('div.dataTables_filter input').addClass('form-control');
    // bind change event to select
    $('#property_id').on('change', function () {
      var property = $(this).val(); // get selected value
      if(property > 0)
      {
        var url = '/tenants?property_id='+property;
      } else var url = '/tenants';
      
      if (url) { // require a URL
          window.location = url; // redirect
      }
      return false;
    });
</script>

@stop