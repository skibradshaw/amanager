   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="myModalLabel">
    Terminate Lease <strong>{{$apartment->name}}</strong> <small>Lease: {{$lease->start->format('n/j/Y')}} - {{$lease->end->format('n/j/Y')}}</small>
    </h4>
  </div>
  {!! Form::open(['route' => ['leases.terminate',$property,$apartment,$lease], 'method' => 'post','class' => 'inline']) !!}
  <div class="modal-body">
    <div class="row">
    
      <div class="col-sm-12">
          {!! Form::label('end','Set End Date:',['for' => 'end','class' => 'col-sm-2 control-label']) !!}
          <div class="form-group @if($errors->first('end')) has-error @endif">
              
              <div class="col-sm-4">
              {!! Form::text('end',\Carbon\Carbon::now()->format('n/j/Y'),['id' => 'end','class' => 'datepicker form-control','placeholder' => 'mm/dd/yyyy','style' => 'position: relative; z-index: 100000;']) !!}
                  <small class="text-danger">{{ $errors->first('end') }}</small>
              </div>
          </div>
        
      </div>
    </div>
  </div>
  <div class="modal-footer">

  <button type="button" class="btn btn-default" data-dismiss="modal">Nevermind</button>
  <button type="submit" id="add" class="btn btn-danger">End This Lease <i class="fa fa-plus-right"></i></button>

  </div>
  {!! Form::close() !!}

  <script>
  $(function() {
    $( ".datepicker" ).datepicker({
      dateFormat: "m/d/yy",
    });
  
  });
  </script>
