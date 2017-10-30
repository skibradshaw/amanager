@extends('layouts.pdf')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-12"> <h2>{{$title}}</h2></div>
                </div>
                <div class="row">
                    <div class="col-lg-12"><hr></div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        @include('leases.partials.ledger')
                    </div>            
                </div>
                <div class="row">
                    <div class="col-lg-12"><hr></div>
                </div>
        <!-- /.row -->
                <div class="row">
                     <div class="col-lg-12">
                        @include('leases.partials.payments_panel')
                    </div>            
                </div>
                <div class="row">
                    <div class="col-lg-12"><hr></div>
                </div>
                <div class="row">          
                     <div class="col-lg-12">
                         @include('leases.partials.deposit_panel')
                    </div> 
                </div>
                
            </div>
        </div>
        <!-- /.row -->
@stop
@section('scripts')
<script>

</script>

@stop