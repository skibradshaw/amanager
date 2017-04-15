@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <h1>{{$title or ''}}</h1>
                <p>&nbsp;</p>
                {!! Form::open(['route' => ['deposits.store'],'class' => 'form-inline']) !!}
                <div class="row">
                    <div class="col-md-3">
                        {!! Form::label('deposit_date', 'Deposit Date:', ['for' => 'deposit_date', 'class' => 'control-label']) !!}
                        {!! Form::text('deposit_date', \Carbon\Carbon::now()->format('n/j/Y'), ['id' => 'deposit_date', 'class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-4 col-md-offset-5 text-right">
                        {!! Form::label('bank_account_id', 'Deposit To:', ['for' => 'bank_account', 'class' => 'control-label']) !!}
                        {!! Form::select('bank_account_id',$bankAccounts->pluck('name','id'), null,['id' => 'bank_account_id','class' => 'form-control']) !!}<br>
                        <small class="pull-right text-right"><a href="{{route('bank_accounts.create')}}" data-toggle="modal" data-target="#largeModal">Add a Bank Account</a><!--  | <a href="{{route('bank_accounts.index')}}">View all Accounts</a> --></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12"><hr></div>
                </div>
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
                                    <th align="center"  class="text-center">Include All {!! Form::checkbox('all', null, 1, ['id' => 'selectAllPayments']) !!}</th>


                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $p)
                                    <tr>
                                        <td>{{$p->lease->apartment->property->name}}</td>
                                        <td><a href="{{route('leases.show',[$p->lease->apartment->property,$p->lease->apartment,$p->lease])}}">{{$p->lease->apartment->name}}</a> {{$p->lease->start->format('n/j/y') . "-" . $p->lease->end->format('n/j/y')}} </td>
                                        <td>{{$p->tenant->fullname}}</td>
                                        <td class="text-center">{{$p->paid_date->format('n/j/Y')}}</td>
                                        <td class="text-center">{{$p->payment_type}}</td>
                                        <td align="right" class="text-right">{{$p->amount_in_dollars}}</td>
                                        <td align="right" class="text-center">{!! Form::checkbox('payment_id', $p->amount/100, 1, ['id' => $p->id,'class' => 'i-check payment']) !!}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Everything is in the Bank!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"><h4><strong>Total Deposit</strong></h4></td>
                                        <td colspan="3" class="text-right"><h4><strong>$<span id="total">{{number_format($payments->sum('amount')/100,2)}}</span></strong></h4></td>
                                        <td colspan="1" class="text-center"><button class="btn btn-success" id="depositSubmit">Make Deposit</button></td>
                                    </tr>
                                </tfoot>    
                            </table>                        
                        </div>                          
                    </div>
                </div>

                {!! Form::close() !!}
                
            </div>
        </div>
        <!-- /.row -->
@stop
@section('scripts')
<script>
    $( "#deposit_date" ).datepicker({
        dateFormat: "m/d/yy",
    });

    /** DATATABLES  */
    $('#payments').DataTable({
       paging: false,
       searching: false,
       aaSorting: [[0, 'asc']]
    });    
    $('div.dataTables_filter input').addClass('form-control');
    // iChecks Functionality: http://icheck.fronteed.com
    $('input').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat',
        // increaseArea: '50%' // optional
      });

    //Show Selected Rent Total
    $(".payment").on('ifChanged',function(event) {
        var total = 0;
        $(".payment:checked").each(function() {
            total += parseInt($(this).val());
        });
        // alert(total);
        if(total == 0)
            $('#depositSubmit').attr('disabled',true);
        else
            $('#depositSubmit').attr('disabled',false);
        $('#total').val(total);
        total = numberWithCommas(total.toFixed(2))
        $('#total').text(total);
    });

    $("#selectAllPayments").on('ifChecked',function(){
      $(".payment").iCheck('check');
     });
    $("#selectAllPayments").on('ifUnchecked',function(){
      $(".payment").iCheck('uncheck');
     });
</script>

@stop