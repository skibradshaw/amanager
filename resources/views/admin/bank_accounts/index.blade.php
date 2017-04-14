@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <a href="{{route('bank_accounts.create')}}" class="btn btn-success pull-right"  data-toggle="modal" data-target="#largeModal">New Bank Account</a>
                <h1>{{$title}}</h1>

                <div class="col-md-7">
                
                    <div class="list-group">
                        @foreach($bankAccounts as $b)
                        <span class="list-group-item">
                            <h4 class="list-group-item-heading">{{$b->name}}</h4>
                            <p class="list-group-item-text">
                            <a href="{{route('bank_accounts.edit',[$b])}}"  data-toggle="modal" data-target="#largeModal">Edit Bank Account</a>
                            </p>
                        </span>
                        @endforeach
                    </div>
                </div>


                
            </div>
        </div>
        <!-- /.row -->
@stop