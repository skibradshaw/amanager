@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <h1>{{$title}}</h1>
                <p class="lead">Bank Deposits</p>
                <div class="col-md-7">
                
                    <div class="list-group">
                        @forelse($deposits as $d)
                        <span class="list-group-item">
                            <a href="{{route('deposits.show',[$bank,$d])}}" class="btn btn-default btn-xs pull-right">View Details</a>                   
                            <h4 class="list-group-item-heading">
                                <strong>Deposit:</strong> {{$d->deposit_date->toDateString()}} by {{$d->creator->firstname or ''}}
                            </h4>
                            <p class="list-group-item-text">
                            <h5>Deposit Amount: {{$d->amount_in_dollars}}</h5>
                            <p>Items: {{$d->payments->count()}}</p>
                            
                            </p>
                        </span>
                        @empty
                        <h4>There are no Deposits for this bank account yet.</h4>
                        
                        @endforelse
                    </div>
                </div>

                
            </div>
        </div>
        <!-- /.row -->
@stop