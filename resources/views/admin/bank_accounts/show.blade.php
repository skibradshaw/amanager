@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <h1>{{$title}}</h1>
                <p class="lead">Bank Account Details</p>
                <div class="col-md-7">
                
                    <div class="list-group">
                        @forelse($deposits as $d)
                        <span class="list-group-item">                   
                            <h4 class="list-group-item-heading">
                                {{$d->deposit_date->toDateTimeString()}}
                            </h4>
                            <p class="list-group-item-text">
                            <h5>Deposit Amount: </h5>
                            <p>Items: </p>
                            
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