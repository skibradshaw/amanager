                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-dollar fa-fw"></i> Payments
                    </div>
                    <div class="panel-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-condensed ledger" id="payments" width="100%">
                                <thead>
                                <tr>
                                    <th align="center" style="cursor:pointer">Date</th>
                                    <th align="center" style="cursor:pointer">Name</th>
                                    <th align="center" style="cursor:pointer">Method</th>
                                    <th align="center" style="cursor:pointer">Type</th>
                                    <th align="center" style="cursor:pointer">Delete</th>
                                    <th align="center" style="cursor:pointer">Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($lease->payments as $p)
                                        <tr>
                                            <td>
                                                @if($p->deposited)
                                                    {{$p->paid_date->format('n/j/Y') }}
                                                @else
                                                    <a href="{{ route('payments.edit',[$property,$apartment,$lease,$p]) }} ">{{$p->paid_date->format('n/j/Y') }} </a>
                                                @endif
                                            </td>
                                            <td>  
                                                @if(!empty($p->memo))
                                                <span style="cursor:pointer; color:blue;text-decoration:underline;" data-toggle="tooltip" title="Notes: {{$p->memo}}" class="">
                                                @else
                                                <span>
                                                @endif
                                                {{ $p->tenant->fullname }}</span>
                                            </td>
                                            <td>{{ $p->method }} {{ (!empty($p->check_no)) ? "#" . $p->check_no : "(# missing)"  }}</td>
                                            <td>{{ $p->payment_type }}</td>
                                            <td align="center" class="text-center">
                                                @if($p->deposited)
                                                    Deposited
                                              @else
                                                    <a href="#" onclick="return confirm('Are you sure?');
                                                                         document.getElementById('delete-payment-{{$p->id}}').submit();"><i class="fa fa-trash fa-fw text-danger"></i></a>
                                                    <form id="delete-payment-{{$p->id}}" action="{{ route('payments.destroy',[$property,$apartment,$lease,$p]) }}" method="POST" style="display: none;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        {{ csrf_field() }}
                                                    </form>  

                                                    
                                                @endif
                                            </td>
                                            <td class="text-right">{{ $p->amount_in_dollars }} </td>
                                        </tr>
                                    @empty
                                    @endforelse     
                                </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><strong>Total Paid:</strong></td>
                                    <td align="right" class="text-right" colspan="4"><strong>{{ money_format('%.2n',$lease->payments()->where('payment_type','!=','Security Deposit')->sum('amount')/100) }}</strong></td>
                                </tr>
                                                       
                            </tfoot>
                            </table>                            
                        </div>
                        
                    </div>
                </div>