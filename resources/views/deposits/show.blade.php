@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <h1>{{$title}} <small>{{$deposit->bank_account->name}}</small> </h1>
                <h4><strong>Deposit #:</strong> {{$deposit->transaction_id or 'Not Provided'}}</h4>
                <h4><strong>Deposit Type: </strong> {{$deposit->deposit_type}} </h4>
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
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $p)
                                    <tr>
                                        <td>{{$p->lease->apartment->property->name}}</td>
                                        <td><a href="{{route('leases.show',[$p->lease->apartment->property,$p->lease->apartment,$p->lease])}}">{{$p->lease->apartment->name}}</a> {{$p->lease->start->format('n/j/y') . "-" . $p->lease->end->format('n/j/y')}} </td>
                                        <td>{{$p->tenant->fullname}}</td>
                                        <td class="text-center">{{$p->paid_date->format('n/j/Y')}}</td>
                                        <td class="text-center">{{$p->payment_type}}</td>
                                        <td align="right" class="text-right">{{$p->amount_in_dollars}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"><h4><strong>Total Deposit</strong></h4></td>
                                        <td colspan="3" class="text-right"><h4><strong><span id="total">{{$deposit->amount_in_dollars}}</span></strong></h4></td>
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