@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <h1>{{$title}}</h1>
                @if(!empty($currentLease))
                <p class="lead">{{$property->name . " " . $apartment->name}} currently has {{$currentLease->tenants->count()}}
                @if(count($currentLease->tenants) == 1)
                 tenant.
                @else
                 tenants.
                @endif
                </p>
                
                <div class="col-md-8">
                    <h3>Current Tenants</h3>
                    <div class="list-group">
                        @foreach($currentLease->tenants as $t)
                        <span class="list-group-item">
                            <h4 class="list-group-item-heading">{{$t->fullname}}</h4>
                            <p class="list-group-item-text">
                            Phone: {{$t->phone}}<br>
                            <a href="mailto:{{$t->email}}">Email: {{$t->email}}</a>
                            </p>
                        </span>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4">
                    <h3>Current Lease Details</h3>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-calendar-o fa-fw"></i> <a href="{{route('leases.show',[$property,$apartment,$currentLease])}}"> Current Lease</a>
                            </div>
                            <div class="panel-body">
                                <p>
                                    <strong>Dates:</strong> {{$currentLease->start->format('n/j/y') . "-" . $currentLease->end->format('n/j/y')}}<br>
                                    <strong>Monthly:</strong> {{$currentLease->monthly_rent_in_dollars}}<br>
                                    <strong>Pet Rent:</strong> {{$currentLease->pet_rent_in_dollars}}<br>
                                    <strong>Security Deposit:</strong> {{$currentLease->deposit_in_dollars}}<br>
                                    <strong>Pet Deposit:</strong> {{$currentLease->pet_deposit_in_dollars}}<br>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
                @else
                <p class="lead">{{$property->name . " " . $apartment->name}} does not currently have a lease in place.</p>
                <a href="{{route('leases.create',[$property,$apartment])}}" class="btn btn-primary">Create a New Lease</a>
                @endif



                
            </div>
            <div class="row"><div class="col-lg-12"><hr></div></div>
            <div class="col-lg-12">
                    <h3>Past Leases</h3>
                    <div class="list-group">
                        @foreach($apartment->pastLeases() as $l)
                        <span class="list-group-item">
                            <h4 class="list-group-item-heading"><a href="{{ route('leases.show',[$property,$apartment,$l]) }}"> {{$l->start->format('n/j/y')}} - {{$l->end->format('n/j/y')}}</a></h4>
                            <p class="list-group-item-text">
                            Tenants: {{$l->tenants->count()}}<br>
                            Monthly Rent: {{$l->monthly_rent_in_dollars}}<br>
                            Pet Rent: {{$l->pet_rent_in_dollars}}<br>
                            Security Deposit: {{$l->deposit_in_dollars}}<br>
                            Pet Deposit: {{$l->pet_deposit_in_dollars}}
                            </p>
                        </span>
                        @endforeach
                    </div>                
            </div>
        </div>
        <!-- /.row -->
        
@stop