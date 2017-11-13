@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                
                
                <h1>{{$title or ''}} <small>{{money_format('%.2n',$allPayments->sum('amount')/100)}} to Deposit</small> </h1>

                <div class="row">
                    @foreach($navProperties as $property)
                    <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">

                            @if(count($property->bank_accounts) === 0)
                            <a href="{{route('bank_accounts.create')}}" class="btn btn-danger pull-right"  data-toggle="modal" data-target="#largeModal">Setup a Bank</a>
                            @else
                            <a href="#" data-toggle="modal" data-target="#depositModal" data-property="{{$property->id}}" class="btn btn-success pull-right open-DepositTypeDialog">Make a Deposit</a>
                            @endif                
                                <h4><i class="fa fa-bank fa-fw"></i> {{$property->name}} Undeposited Funds</h4>
                            </div>
                            <div class="panel-body">
                                <h4><strong>Total Payments:</strong> {{count($property->getUndepositedPayments())}}</h4>
                                <h4><strong>Amount to Deposit:</strong> {{money_format('%.2n',$property->getUndepositedPayments()->sum('amount')/100)}}</h4>
                                <h4><strong>Rent Payments:</strong> {{money_format('%.2n',$property->getUndepositedPayments()->filter(function($p){ if($p->payment_type == 'Rent' || $p->payment_type == 'Fee') return true; })->sum('amount')/100)}}</h4>
                                <h4><strong>Security Deposit Payments:</strong> {{money_format('%.2n',$property->getUndepositedPayments()->filter(function($p){ if($p->payment_type == 'Security Deposit') return true; })->sum('amount')/100)}}</h4>

                               
                                </p>
                            </div>
                        </div>                        
                    </div>
                    @endforeach
                </div>  
                <div class="row"><hr></div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover responsive" id="payments" width="100%">
                                <thead>
                                <tr>
                                    <th align="center" style="cursor:pointer">Property</th>
                                    <th align="center" style="cursor:pointer">Apartment/Lease</th>
                                    <th align="center" style="cursor:pointer" class="text-center">Tenant</th>
                                    <th align="center" style="cursor:pointer" class="text-center">Payment Date</th>
                                    <th align="center" style="cursor:pointer" class="text-center">Payment Type</th>
                                    <th align="right" style="cursor:pointer" class="text-center">Amount</th>

                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($allPayments as $p)
                                    <tr>
                                        <td>{{$p->lease->apartment->property->name}}</td>
                                        <td><a href="{{route('leases.show',[$p->lease->apartment->property,$p->lease->apartment,$p->lease])}}">{{$p->lease->apartment->name}}</a> {{$p->lease->start->format('n/j/y') . "-" . $p->lease->end->format('n/j/y')}} </td>
                                        <td>{{$p->tenant->fullname}}</td>
                                        <td class="text-center">{{$p->paid_date->format('n/j/Y')}}</td>
                                        <td class="text-center">{{$p->payment_type}}</td>
                                        <td align="right" class="text-right">{{$p->amount_in_dollars}}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Everything is in the Bank!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"><strong>Balance</strong></td>
                                        <td colspan="4" class="text-right"><strong>{{money_format('%.2n',$allPayments->sum('amount')/100)}}</strong></td>
                                    </tr>
                                </tfoot>    
                            </table>                        
                        </div>                        
                    </div>

                </div>              
            </div>            
        </div>
        <!-- /.row -->

    <div class="modal fade" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="mediumModal">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">
                What type of Deposit will this be?
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                
                    <div class="col-sm-12">
                    {!! Form::select('deposit_type', $depositTypes->prepend('Please Choose...'), null, ['id' => 'deposit_types', 'class' => 'form-control']) !!}
                    {!! Form::hidden('property_id', '', ['id' => 'modalPropertyId']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row"></div>
            </div>
        </div>
      </div>
    </div>
@stop

@section('scripts')
<script>
    /** DATATABLES  */
    $('#payments').DataTable({
       paging: false,
       searching: true,
       aaSorting: [[3, 'asc'],[1,'asc']]
    });    
    $('div.dataTables_filter input').addClass('form-control');

    // Set the property ID on the Modal Select
    $(".open-DepositTypeDialog").click(function () {
        $('#modalPropertyId').val($(this).data('property'));
    });

    // bind change event to select
    $('#deposit_types').on('change', function () {
      var type = $(this).val(); // get selected value
      var property = $('#modalPropertyId').val();
      var url = '/reports/undeposited/'+property+'/confirm?type='+type;
      if (url && type > 0) { // require a URL
          window.location = url; // redirect
      }
      return false;
    });
</script>

@stop