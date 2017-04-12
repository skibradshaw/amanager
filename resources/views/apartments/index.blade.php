@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <p><a href="#" data-toggle="modal" data-target="#apartmentModal" class="btn btn-primary pull-right">Create a New Lease</a></p>
                <h1>{{$title}}</h1>
                
<!--                 <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Primum Theophrasti, Strato, physicum se voluit; Hoc est vim afferre, Torquate, sensibus, extorquere ex animis cognitiones verborum, quibus inbuti sumus. Plane idem, inquit, et maxima quidem, qua fieri nulla maior potest. Cum autem negant ea quicquam ad beatam vitam pertinere, rursus naturam relinquunt. Ut necesse sit omnium rerum, quae natura vigeant, similem esse finem, non eundem. Et quidem, inquit, vehementer errat;</p>
                <ul class="list-unstyled">
                    <li>Bootstrap v3.3.7</li>
                    <li>jQuery v1.11.1</li>
                </ul> -->
                @if(count($apartments))
                <h3>Leased Apartments</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-condensed  table-hover responsive" id="apartments" width="100%">
                        <thead>
                        <tr>
                            <th align="center" style="cursor:pointer">Apartment</th>
                            <th align="center" style="cursor:pointer" class="text-center">Number</th>
                            <th align="center" style="cursor:pointer" class="text-center">Open Balance</th>
                            <th align="center" style="cursor:pointer" class="text-center">Lease Ends</th>
                            <th align="center" class="text-center">Lease Summary</th>
                            <th align="left" style="cursor:pointer" class="text-center">New Lease</th>
                        </tr>
                        </thead>
                        <tbody>

                                @forelse($apartments as $a)
                                    <tr>
                                        @if(isset($a->currentLease()->id))
                                            <td><a href="{{ route('apartments.show',[$property, $a]) }}">{{ $a->property->name }} {{$a->name}}</a></td>
                                            <td class="text-center">{{$a->number}}</td>
                                            <td align="right" class="text-center">{{$a->currentLease()->open_balance_in_dollars }}</td>
                                            <td align="right" class="text-center" nowrap>
                                                    <a href="{{ route('leases.show',[$property,$a,$a->currentLease()]) }} ">

                                                    {{  $a->currentLease()->end->format('n/j/y') }}
                                                    </a>
                                            </td>
                                            <td align="left" class="text-center">{{$a->currentLease()->start->format('n/j/y') . " - " . $a->currentLease()->end->format('n/j/y')}} with {{($a->currentLease()->tenants->count() == 1) ? $a->currentLease()->tenants->count() . " Tenant" : $a->currentLease()->tenants->count() . " Tenants" }}</td>
                                        @else
                                            <td><a href="{{ route('apartments.show',[$property, $a]) }}">{{ $a->property->name }} {{$a->name}}</a></td>
                                            <td class="text-center">{{$a->number}}</td>
                                            <td align="right" class="text-right">&nbsp</td>
                                            <td align="center" class="text-center">&nbsp</td>
                                            <td align="center" class="text-center">&nbsp</td>
                                        @endif

                                        <td align="center" class="text-center">
                                                <a href="{{ route('leases.create',[$property,$a]) }}" class="btn btn-default btn-xs">Create Lease</a>       
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
        <div class="row">
            <div class="col-lg-12">

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
                                <td>&nbsp</td>                           
                                <td align="center" class="text-center">
                                        <a href="{{ route('leases.create',[$property,$a]) }}" class="btn btn-default btn-xs btn-block">Create Lease</a>
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
                    {!! Form::select('apartment_id', $allApartments->pluck('name','id'), null, ['id' => 'apt_choice', 'class' => 'form-control']) !!}
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
       aaSorting: [[1, 'asc']]
    });    
    $('#vacantApartments').DataTable({
       paging: false,
       searching: true,
       aaSorting: [[1, 'asc']]
    });

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