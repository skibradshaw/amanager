   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="myModalLabel">
    Add a Fee <strong>{{$apartment->name}}</strong> <small>Lease: {{$lease->start->format('n/j/Y')}} - {{$lease->end->format('n/j/Y')}}</small>
    </h4>
  </div>
  {!! Form::open(['route' => ['fees.store',$property,$apartment,$lease],'class' => 'form-horizontal']) !!}
  <div class="modal-body">
    <div class="row">
        @include('fees.partials.edit')
    </div>
  </div>              
    
  <div class="modal-footer">

  <button type="button" class="btn btn-default" data-dismiss="modal">Nevermind</button>
  <button type="submit" id="add" class="btn btn-default">Add Fee<i class="fa fa-plus-right"></i></button>

  </div>


    {!! Form::close() !!}