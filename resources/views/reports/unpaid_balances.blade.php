@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-5"> <h1>{{$title}}</h1></div>
                    <div class="col-md-3 col-md-offset-4 text-right">
                        {!! Form::select('property_id',$navProperties->pluck('name','id')->prepend('Show All Properties',''), (!is_null($property)) ? $property->id : null,['id' => 'property_id','class' => 'form-control pull-right']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12"><hr></div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover responsive" id="unpaid" width="100%">
                                <thead>
                                <tr>
                                    <th align="center" style="cursor:pointer">Apartment</th>
                                    <th align="center" style="cursor:pointer">Lease</th>
                                    <th align="center" style="cursor:pointer" class="text-center">Tenants</th>
                                    <th align="center" style="cursor:pointer" class="text-center">Remarks</th>
                                    <th align="center" style="cursor:pointer" class="text-center">Rent Balance</th>
                                    <th align="right" style="cursor:pointer" class="text-center">Deposit Balance</th>
                                    <th align="center" style="cursor:pointer" class="text-center">Send</th>



                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($unpaidLeases as $l)
                                    <tr data-toggle="collapse" data-target="#accordion" class="clickable">
                                        <td nowrap="nowrap">{{$l->apartment->property->name}} {{$l->apartment->name}}</td>
                                        <td><a href="{{route('leases.show',[$l->apartment->property,$l->apartment,$l])}}">{{$l->apartment->name}}</a> {{$l->start->format('n/j/y') . "-" . $l->end->format('n/j/y')}} </td>
                                        <td class="text-left">
                                            @if(count($l->tenants)>0)
                                            <dl class="dl-horizontal text-left">
                                                @foreach($l->tenants as $t)
                                                    <dt>{{$t->fullname}}</dt><dd>{{$t->phone or ''}} {{$t->email}}</dd>
                                                @endforeach
                                            </dl>
                                            @else
                                            Add Tenants
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($l->pet_rent > 0)
                                            <i class="fa fa-paw fa-fw" data-toggle="tooltip" title="Pets {{money_format('%.2n',$l->pet_rent/100)}}/mo"></i>
                                            @endif
                                            @if($l->fees->sum('amount') > 0)
                                            <i class="fa fa-usd fa-fw" data-toggle="tooltip" title="Fees {{money_format('%.2n',$l->fees->sum('amount')/100)}}"></i>
                                            @endif                                            
                                        </td>
                                        <td align="right" class="text-right">{{$l->open_balance_in_dollars}}</td>
                                        <td align="right" class="text-right">{{$l->deposit_balance_in_dollars}}</td>
                                        <td><button class="btn btn-default btn-sm">Send Notice</button></td>
                                    </tr>                                  
                                    @empty

                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4"><h4><strong>Totals:</strong></h4></td>
                                        <td class="text-right"><h4><strong><span id="total">{{money_format('%.2n',$totalUnpaidRent/100)}}</span></strong></h4></td>
                                        <td class="text-right"><h4><strong><span id="total">{{money_format('%.2n',$totalUnpaidDeposits/100)}}</span></strong></h4></td>
                                        <td colspan="1" ></td>
                                       
                                    </tr>
                                </tfoot>    
                            </table>                        
                        </div>                          
                    </div>
                </div>

                
            </div>
        </div>
        <!-- /.row -->
@stop
@section('scripts')
<script>
    // Tooltips
    $('[data-toggle="tooltip"]').tooltip();

    /** DATATABLES  */
    $('#unpaid').DataTable({
       paging: false,
       searching: true,
       aaSorting: [[1, 'asc']],
       columnDefs: [  
        { targets: '4', aaSorting: false},
        // { targets: '_all', visible: false }
        ]
    });    
    $('div.dataTables_filter input').addClass('form-control');
    // bind change event to select
    $('#property_id').on('change', function () {
      var property = $(this).val(); // get selected value
      var url = '/reports/unpaid_balances/'+property;
      if (url) { // require a URL
          window.location = url; // redirect
      }
      return false;
    });
</script>

@stop