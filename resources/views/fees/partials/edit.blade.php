    <div class="form-group @if($errors->first('item_name')) has-error @endif">
        {!! Form::label('item_name','Type of Fee:',['for' => 'item_name','class' => 'col-sm-4 control-label']) !!}
        <div class="col-sm-4">
				{!! Form::select('item_name',$fee_types, null,['id' => 'item_name','class' => 'form-control']) !!}
            <small class="text-danger">{{ $errors->first('item_name') }}</small>
        </div>
    </div>
    <div class="form-group @if($errors->first('due_date')) has-error @endif">
        {!! Form::label('due_date','Due Date:',['for' => 'due_date','class' => 'col-sm-4 control-label']) !!}
        <div class="col-sm-4">
				{!! Form::text('due_date',\Carbon\Carbon::now()->format('n/j/Y'),['id' => 'due_date','class' => 'datepicker form-control','placeholder' => 'mm/dd/yyyy','style' => 'position: relative; z-index: 100000;']) !!}
            <small class="text-danger">{{ $errors->first('due_date') }}</small>
        </div>
    </div>
    <div class="form-group @if($errors->first('amount')) has-error @endif">
        {!! Form::label('amount','Amount:',['for' => 'amount', 'class' => 'col-sm-4 control-label']) !!}
        <div class="col-sm-6">
            <div class="input-group">
                <span class="input-group-addon">$</span>
                {!! Form::text('amount',null,['id' => 'amount','class' => 'form-control']) !!}
            </div>
            <small class="text-danger">{{ $errors->first('amount') }}</small>
        </div>
    </div>
    <div class="form-group @if($errors->first('note')) has-error @endif">
        {!! Form::label('note','Notes:',['for' => 'note','class' => 'col-sm-4 control-label']) !!}
        <div class="col-sm-6">
                {!! Form::textarea('note',null,['id' => 'note','class' => 'form-control','rows' => 2]) !!}
            <small class="text-danger">{{ $errors->first('note') }}</small>
        </div>
    </div>    