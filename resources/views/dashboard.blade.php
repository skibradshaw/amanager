@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <h1>Dashboard</h1>
                <div class="row">
                    @foreach($navProperties as $property)
                    <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a href="#" data-toggle="modal" data-target="#{{$property->id}}Modal" class="btn btn-primary pull-right btn-xs">Create a New Lease</a>
                                <h4><i class="fa fa-building fa-fw"></i> {{$property->name}}</h4>
                            </div>
                            <div class="panel-body">
                                <h4><strong>Unpaid Balances:</strong> Rents + Security Deposits?</h4>
                                <h4><strong>Undeposited Funds:</strong> Rents + Security Deposits?</h4>
                                <h4><strong>Vacant Apartments:</strong> {{$property->apartments()->has('leases','=',0)->count()}} </h4>

                                <div class="col-lg-12"><hr></div>
                                <h5><strong>New Leases:</strong> </h5>
                                 <div class="table-responsive">
                                    <table class="table table-striped table-condensed ledger" id="payments" width="100%">
                                        <thead>
                                        <tr>
                                            <th align="center">Date Created</th>
                                            <th align="center">Apartment</th>
                                            <th align="center">Created By</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($property->leases()->take(3)->orderBy('created_at','desc')->get() as $l)
                                                <tr>
                                                    <td>{{$l->created_at->format('n/j/Y') }}
                                                        
                                                    </td>
                                                    <td>{{ $l->apartment->property->name . ' ' . $l->apartment->name }}</td>
                                                    
                                                    <td>Tim</td>
                                                </tr>
                                            @empty
                                            @endforelse     
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5"><strong></strong></td>
                                                
                                            </tr>
                                                                   
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="col-lg-12"><hr></div>
                                <h5><strong>Recent Payments:</strong> </h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-condensed ledger" id="payments" width="100%">
                                        <thead>
                                        <tr>
                                            <th align="center">Date</th>
                                            <th align="center">Tenant</th>
                                            <th align="center">Apartment</th>
                                            <th align="center">Type</th>
                                            <th align="center">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(App\Payment::whereHas('lease',function($q) use ($property) { $q->whereHas('apartment',function($y) use ($property){ $y->where('property_id',$property->id); }); })->where('payment_type','<>','Deposit')->orderBy('paid_date','desc')->take(3)->get() as $p)
                                                <tr>
                                                    <td>{{$p->paid_date->format('n/j/Y') }}
                                                        
                                                    </td>
                                                    <td>{{ $p->tenant->fullname }}</td>
                                                    
                                                    <td>{{ $p->lease->apartment->property->name . ' ' . $p->lease->apartment->name }}</td>
                                                    <td>{{ $p->payment_type }}</td>
                                                    <td class="text-right">{{ $p->amount_in_dollars }} </td>
                                                </tr>
                                            @empty
                                            @endforelse     
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5"><strong></strong></td>
                                                
                                            </tr>
                                                                   
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                        </div>                        
                    </div>
                    <div class="modal fade" id="{{$property->id}}Modal" tabindex="-1" role="dialog" aria-labelledby="smallModal">
                      <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                             <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="myModalLabel">
                                Which Apartment?
                                </h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                
                                    <div class="col-sm-12">
                                    {!! Form::select('apartment_id', $property->apartments->pluck('name','id'), null, ['class' => 'form-control apt_choice']) !!}
                                    </div>
                                </div>
                            </div>
                            <script>



                            </script>
                        </div>
                      </div>
                    </div>                    
                    @endforeach
                </div>                


                
            </div>
        </div>
        <!-- /.row -->
@stop
@section('scripts')
<script>
    
    // bind change event to select
    $('.apt_choice').on('change', function () {
      var apartment = $(this).val(); // get selected value
      var url = '/properties/{{$property->id}}/apartments/'+apartment+'/leases/create';
      if (url) { // require a URL
          window.location = url; // redirect
      }
      return false;
    });
</script>

@stop