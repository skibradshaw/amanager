
    <div class="form-group @if($errors->first('tenant_id')) has-error @endif">
        {!! Form::label('tenant_id','Select a Tenant:',['for' => 'tenant_id','class' => 'col-sm-4 control-label']) !!}
        <div class="col-sm-4">
				{!! Form::select('tenant_id',$tenants,(isset($tenant->id)) ? $tenant->id : null,['id' => 'tenant_id','class' => 'form-control']) !!}
            <small class="text-danger">{{ $errors->first('tenant_id') }}</small>
        </div>
    </div>
    <div class="form-group @if($errors->first('paid_date')) has-error @endif">
        {!! Form::label('paid_date','Date Paid:',['for' => 'paid_date','class' => 'col-sm-4 control-label']) !!}
        <div class="col-sm-4">
				{!! Form::text('paid_date',null,['id' => 'paid_date','class' => 'datepicker form-control','placeholder' => 'mm/dd/yyyy','style' => 'position: relative; z-index: 100000;']) !!}
            <small class="text-danger">{{ $errors->first('paid_date') }}</small>
        </div>
    </div>
    <div class="form-group @if($errors->first('payment_type')) has-error @endif">
        {!! Form::label('payment_type','Type:',['for' => 'payment_type','class' => 'col-sm-4 control-label']) !!}
        <div class="col-sm-6">
				{!! Form::select('payment_type',$payment_types, (isset($payment_type)) ? $payment_type : null,['id' => 'payment_type','class' => 'form-control']) !!}
            <small class="text-danger">{{ $errors->first('payment_type') }}</small>
        </div>
    </div>
    <div class="form-group @if($errors->first('amount')) has-error @endif">
        {!! Form::label('amount','Payment Amount:',['for' => 'amount', 'class' => 'col-sm-4 control-label']) !!}
        <div class="col-sm-6">
            <div class="input-group">
                <span class="input-group-addon">$</span>
                {!! Form::text('amount',(isset($payment)) ? str_replace('$','',$payment->amount_in_dollars) : null,['id' => 'amount','class' => 'form-control']) !!}
            </div>
            <small class="text-danger">{{ $errors->first('amount') }}</small>
        </div>
    </div>
    <div class="form-group @if($errors->first('method')) has-error @endif">
        {!! Form::label('method','Payment Method:',['for' => 'method','class' => 'col-sm-4 control-label']) !!}
        <div class="col-sm-6">
				{!! Form::select('method',$paymentMethods,'Check',['id' => 'method','class' => 'form-control']) !!}
            <small class="text-danger">{{ $errors->first('method') }}</small>
        </div>
    </div>
    <div class="form-group @if($errors->first('check_no')) has-error @endif">
        {!! Form::label('check_no','Check # or Last 4 of CC:',['for' => 'check_no','class' => 'col-sm-4 control-label']) !!}
        <div class="col-sm-6">
                {!! Form::text('check_no',null,['id' => 'check_no','class' => 'form-control']) !!}
            <small class="text-danger">{{ $errors->first('check_no') }}</small>
        </div>
    </div>
    <div class="form-group @if($errors->first('memo')) has-error @endif">
        {!! Form::label('memo','Memo:',['for' => 'memo','class' => 'col-sm-4 control-label']) !!}
        <div class="col-sm-6">
                {!! Form::textarea('memo',null,['id' => 'memo','class' => 'form-control','rows' => 2]) !!}
            <small class="text-danger">{{ $errors->first('memo') }}</small>
        </div>
    </div>
	