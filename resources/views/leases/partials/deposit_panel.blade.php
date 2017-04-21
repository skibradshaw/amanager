                <div class="panel @if($lease->depositBalance()>0) panel-danger @else panel-default @endif">
                    <div class="panel-heading">
                    @if($lease->depositBalance() > 0)
                    <span class="text-danger pull-right">Depsoit Balance: {{$lease->deposit_balance_in_dollars}}</span>
                    @else
                    <span class="text-success pull-right">Deposit Balance: {{$lease->deposit_balance_in_dollars}}</span>
                    @endif                
                        <i class="fa fa-money fa-fw"></i> Security Deposits
                    </div>
                    <div class="panel-body">
                    <div class="table-responsive">
                        <table id="deposit" class="table table-striped table-condensed ledger" width="100%">
                            <thead>
                                <tr>
                                  <th>Tenant</th>
                                  <th nowrap="" align="right" class="text-right">Deposit Payments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lease->tenants as $t)
                                <tr>
                                    <td>{{ $t->fullname }}</td>
                                    <td align="right" class="text-right">{{ money_format('%.2n',$lease->payments()->where('tenant_id',$t->id)->where('payment_type','Security Deposit')->sum('amount')/100) }}</td>
                                </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><strong>Total Due:</strong></td>
                                    <td align="right" class="text-right"><strong>{{ $lease->deposit_due_in_dollars }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Paid:</strong></td>
                                    <td align="right" class="text-right"><strong>{{ money_format('%.2n',$lease->payments()->where('payment_type','Security Deposit')->sum('amount')/100) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Balance Due:</strong></td>
                                    <td align="right" class="text-right">
                                        <strong>
                                        @if($lease->depositBalance()<>0)
                                            <span>{{ $lease->deposit_balance_in_dollars}}</span>
                                        @else
                                            <span>{{ $lease->deposit_balance_in_dollars }}</span>
                                        @endif
                                        </strong>
                                    </td>
                                </tr>                       
                            </tfoot>
                        </table>                        
                    </div>
                    <!-- <a href="#" class="btn btn-default btn-sm pull-right">Create a New Deposit</a>                         -->
                    </div>
                </div>