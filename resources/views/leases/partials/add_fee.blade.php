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
    @if(Request::has('month') && $lease->monthFees(Request::input('month'),Request::input('year')) > 0)
    <div class="row">
        <div class="col-md-12">
          <h4>{{date("F", mktime(0, 0, 0, Request::input('month'), 15))}} Fee Details</h4>
          <div class="list-group">
            @foreach($lease->monthFeesDetails(Request::input('month'),Request::input('year')) as $f)
            <a href="{{route('fees.edit',[$property,$apartment,$lease,$f])}}" class="list-group-item">{{$f->due_date->format('n/j/Y')}} - {{$f->item_name}}: {{$f->amount_in_dollars}}</a>
            @endforeach
          </div>

        </div>
    </div>
    @endif
  </div>              
    
  <div class="modal-footer">

  <button type="button" class="btn btn-default" data-dismiss="modal">Nevermind</button>
  <button type="submit" id="add" class="btn btn-default">Add Fee<i class="fa fa-plus-right"></i></button>

  </div>


    {!! Form::close() !!}