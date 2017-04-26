@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <a href="{{route('bank_accounts.create')}}" class="btn btn-success pull-right"  data-toggle="modal" data-target="#largeModal">New Bank Account</a>
                <h1>{{$title}}</h1>

                <div class="row">
                    @foreach($navProperties as $property)
                    <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">               
                                <h4><i class="fa fa-bank fa-fw"></i> {{$property->name}} Bank Accounts</h4>
                            </div>
                            <div class="panel-body">
                                <div class="list-group">
                                    @forelse($property->bank_accounts as $b)
                                    <span class="list-group-item">
                                        @if(count($b->deposits) == 0)
                                        <a href="#" onclick="confirm('Are you sure?');
                                                             document.getElementById('delete-bank').submit();"><i class="fa fa-trash fa-1x text-danger pull-right"></i></a>
                                        <form id="delete-bank" action="{{ route('bank_accounts.destroy',$b) }}" method="POST" style="display: none;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            {{ csrf_field() }}
                                        </form>
                                        @endif
                                        <h4 class="list-group-item-heading">
                                            {{$b->name}}
                                        </h4>
                                        <p class="list-group-item-text">
                                        <a href="{{route('bank_accounts.show',[$b])}}">View Deposits</a> | 
                                        <a href="{{route('bank_accounts.edit',[$b])}}"  data-toggle="modal" data-target="#largeModal">Edit Bank Account</a>
                                        </p>
                                    </span>
                                    @empty
                                    <h4>There are no Bank Accounts setup.  Would you like to <a href="{{route('bank_accounts.create')}}" class="label label-success"  data-toggle="modal" data-target="#largeModal">set one up</a> ?</h4>
                                    
                                    @endforelse
                                </div>


                            </div>
                        </div>                        
                    </div>
                    @endforeach
                </div>  
            </div>
        </div>
        <!-- /.row -->


@stop