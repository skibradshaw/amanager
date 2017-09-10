@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <p><a href="#" data-toggle="modal" data-target="#apartmentModal" class="btn btn-primary pull-right">Create a New Lease</a></p>
                <h1>{{$title}}</h1>
                
                @if(count($vacantApartments))
                <h3>Vacant Apartments</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-condensed  table-hover responsive" id="vacantApartments" width="100%">
                    <thead>
                    <tr>
                        <th align="center" style="cursor:pointer">Apartment</th>
                        <th align="center" style="cursor:pointer" class="text-center">Number</th>
                        <th align="center" style="cursor:pointer" class="text-center">Last Lease</th>
                        <th align="left" style="cursor:pointer" class="text-center">New Lease</th>
                    </tr>
                    </thead>
                    <tbody>

                            @forelse($vacantApartments as $a)
                                <tr>
                                    <td><a href="{{ route('apartments.show',[$property, $a]) }}">{{ $a->property->name }} {{$a->name}}</a></td>
                                    <td class="text-center">{{$a->number}}</td> 
                                    <td>{{$a->leases->last()}} </td>                           
                                    <td align="center" class="text-center">
                                            <a href="{{ route('leases.create',[$property,$a]) }}" class="btn btn-default btn-sm">Create Lease</a>
                                    </td>
                                </tr>       
                            @empty
                                <tr>
                                    <td colspan="6">None Added</td>
                                </tr>
                            @endforelse

                    </tbody>
                    </table>
                </div>
                @endif                

                @if(count($apartments))
                <h3>Leased Apartments</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-condensed  table-hover responsive" id="apartments" width="100%">
                        <thead>
                        <tr>
                            <th align="center" style="cursor:pointer">Apartment</th>
                            <th align="center" style="cursor:pointer" class="text-center">Number</th>
                            <th align="center" style="cursor:pointer" class="text-center">Notes</th>
                            <th align="center" style="cursor:pointer" class="text-center">Rent Balance</th>
                            <th align="center" style="cursor:pointer" class="text-center">Security Deposit Balance</th>
                            <th align="center" style="cursor:pointer" class="text-center">Lease Ends</th>
                            <th align="center" class="text-center">Lease Summary</th>
                            <th align="left" style="cursor:pointer" class="text-center">New Lease</th>
                        </tr>
                        </thead>
                        <tbody>

                                @forelse($apartments as $a)
                                    <tr>
                                        @if(isset($a->nextLease()->id))
                                            <td>
                                                <a href="{{ route('apartments.show',[$property, $a]) }}">
                                                {{ $a->property->name }} {{$a->name}}
                                                </a>
                                            </td>
                                            <td class="text-center">{{$a->number}}</td>
                                            <td class="text-center">
                                                @if($a->nextLease()->pet_rent > 0)
                                                <i class="fa fa-paw fa-fw" data-toggle="tooltip" title="Pets: {{money_format('%.2n',$a->nextLease()->pet_rent/100)}}/mo"></i>
                                                @endif
                                                @if($a->nextLease()->fees->sum('amount') > 0)
                                                <i class="fa fa-usd fa-fw" data-toggle="tooltip" title="Total Fees: {{money_format('%.2n',$a->nextLease()->fees->sum('amount')/100)}}"></i>
                                                @endif                                            
                                            </td>
                                            <td align="right" class="text-center">{{$a->nextLease()->rent_balance_in_dollars}}</td>
                                            <td align="right" class="text-center">{{$a->nextLease()->deposit_balance_in_dollars}}</td>
                                            <td align="right" class="text-center" nowrap>
                                                    <a href="{{ route('leases.show',[$property,$a,$a->nextLease()]) }} ">

                                                    {{  $a->nextLease()->end->format('n/j/y') }}
                                                    </a>
                                            </td>
                                            <td align="left" class="text-center">{{$a->nextLease()->start->format('n/j/y') . " - " . $a->nextLease()->end->format('n/j/y')}} with {{($a->nextLease()->tenants->count() == 1) ? $a->nextLease()->tenants->count() . " Tenant" : $a->nextLease()->tenants->count() . " Tenants" }}</td>
                                        @else
                                            <!-- <td><a href="{{ route('apartments.show',[$property, $a]) }}">{{ $a->property->name }} {{$a->name}}</a></td>
                                            <td class="text-center">{{$a->number}}</td>
                                            <td align="right" class="text-right">&nbsp</td>
                                            <td align="center" class="text-center">&nbsp</td>
                                            <td align="center" class="text-center">&nbsp</td> -->
                                        @endif

                                        <td align="center" class="text-center">
                                                <a href="{{ route('leases.create',[$property,$a]) }}" class="btn btn-default btn-sm">Create Lease</a>       
                                        </td>
                                    </tr>       
                                @empty
                                    <tr>
                                        <td>None Added</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforelse

                        </tbody>
                        </table>
                    </div>
                <hr>    
                @endif
            </div>
        </div>        
        <!-- /.row -->
    <div class="modal fade" id="apartmentModal" tabindex="-1" role="dialog" aria-labelledby="smallModal">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">
                Which Apartment?
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                
                    <div class="col-sm-12">
                    {!! Form::select('apartment_id', $allApartments->pluck('name','id')->prepend('Choose Apartment..'), null, ['id' => 'apt_choice', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <script>



            </script>
        </div>
      </div>
    </div>

@stop
@section('scripts')
<script>
    /** DATATABLES  */
    $('#apartments').DataTable({
       paging: false,
       searching: true,
       aaSorting: [[1, 'asc']],
       columnDefs: [
            { 'orderData':[1], 'targets': [0] },
            {
                "targets": [ 1 ],
                "visible": false,
                "searchable": false
            }
        ]
    });    
    $('#vacantApartments').DataTable({
       paging: false,
       searching: true,
       aaSorting: [[1, 'asc']],
       columnDefs: [
            { 'orderData':[1], 'targets': [0] },
            {
                "targets": [ 1 ],
                "visible": false,
                "searchable": false
            }
        ]
    });
    $('div.dataTables_filter input').addClass('form-control');
    
    // bind change event to select
    $('#apt_choice').on('change', function () {
      var apartment = $(this).val(); // get selected value
      var url = '/properties/{{$property->id}}/apartments/'+apartment+'/leases/create';
      if (url) { // require a URL
          window.location = url; // redirect
      }
      return false;
    });
</script>

@stop