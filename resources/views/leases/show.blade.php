@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <h1>{{$property->name . " " . $apartment->name}} <small>Lease: {{$lease->start->format('n/j/Y')}} - {{$lease->end->format('n/j/Y')}}</small></h1>                
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-smile-o fa-fw"></i> Tenants
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-condensed  table-hover responsive" id="tenants" width="100%">
                            <thead>
                                <tr>
                                    <th align="center" style="cursor:pointer">Name</th>
                                    <th align="center" style="cursor:pointer" class="text-center">Phone</th>
                                    <th align="center" style="cursor:pointer" class="text-center">Email</th>
                                    <th align="center" style="cursor:pointer" class="text-center">SubLease</th>
                                    <th align="left" style="cursor:pointer" class="text-center">Payment</th>
                                </tr>
                            </thead>
                            <tbody> 
                            @foreach($lease->tenants as $t)
                                <tr>
                                    <td>{{$t->fullname}}</td>
                                    <td class="text-center">{{$t->phone}}</td>
                                    <td class="text-center">{{$t->email}}</td>
                                    <td class="text-center"></td>
                                    <td class="text-center"><a href="{{route('payments.create',[$property,$apartment,$lease,'tenant_id' => $t->id])}}" class="btn btn-success btn-xs">Add Payment</a href=" "></td>
                                </tr>
                            @endforeach                            
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="1"></td>
                                    <td colspan="2"></td>
                                </tr>                                
                            </tfoot>
                        </table> 
                        <a href="{{route('leases.add_tenant.show',[$property,$apartment,$lease])}}" data-toggle="modal" data-target="#largeModal" class="btn btn-primary">Add Tenant</a>                      
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-calendar-o fa-fw"></i> Lease Details
                    </div>
                    <div class="panel-body">
                        <p>
                            <strong>Dates:</strong> {{$lease->start->format('n/j/y') . " - " . $lease->end->format('n/j/y')}}<br>
                            <strong>Monthly:</strong> {{$lease->monthly_rent_in_dollars}}<br>
                            <strong>Pet Rent:</strong> {{$lease->pet_rent_in_dollars}}<br>
                            <strong>Security Deposit:</strong> {{$lease->deposit_in_dollars}}<br>
                            <strong>Pet Deposit:</strong> {{$lease->pet_deposit_in_dollars}}<br>
                            <strong>Rent Balance:</strong> {{$lease->open_balance_in_dollars}}</br>
                            <strong>Deposit Balance:</strong> {{$lease->deposit_balance_in_dollars}}</br>
                        </p>
                        <a href="{{route('leases.terminate',[$property,$apartment,$lease])}}" data-toggle="modal" data-target="#largeModal" class="btn btn-danger btn-block">End Lease</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                @include('leases.partials.ledger')
            </div>            
        </div>
        <!-- /.row -->
        <div class="row">
             <div class="col-lg-6">
                @include('leases.partials.payments_panel')
            </div>            
             <div class="col-lg-6">
                 @include('leases.partials.deposit_panel')
            </div> 
        </div>
        <!-- /.row -->
        <div class="row">
            
        </div>
        <!-- /.row -->


@stop

@section('scripts')
<script>
    /** DATATABLES  */
    $('#payments').DataTable({
       paging: false,
       searching: false,
       aaSorting: [[0, 'desc']]
    });

    //Jquery autocomplete search
    $(function()
    {
         $( "#q" ).autocomplete({
          //source: "/tenants/search",
          source: [
            @foreach($tenants as $tenant)
            {!! '{label: "' . $tenant->fullname . '", value: "'. $tenant->id . '"},' !!}
            @endforeach
          ],          
          minLength: 0,
          appendTo: "#largeModal",
          focus: function(event, ui) {
            // prevent autocomplete from updating the textbox
            event.preventDefault();
            // manually update the textbox
            $(this).val(ui.item.label);
            },
          select: function(event, ui) {
            // prevent autocomplete from updating the textbox
            event.preventDefault();
            // manually update the textbox and hidden field
            $(this).val(ui.item.label);
            $('#tenant_id').val(ui.item.value);
            $('#add').removeAttr('disabled');
          }
        });
    });    
</script>
@stop