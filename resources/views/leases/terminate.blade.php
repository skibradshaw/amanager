
<h4 class="small">Terminate Lease<br>{{ $title }}</h4>
{!! Form::open(['route' => ['leases.terminate',$property,$apartment,$lease], 'method' => 'post']) !!}
{!! Form::hidden('lease_id',$lease->id) !!}
<div class="row">
    <div class="large-3 columns">
        {!! Form::label('enddate','End Date:',['class' => 'inline']) !!}    
    </div>
    <div class="large-4 columns left">
        {!! Form::text('enddate',\Carbon\Carbon::now()->format('m/d/Y'),['id' => 'datepicker']) !!}
    </div>
</div>
<button type="submit" id="terminateLease" class="radius button alert">Terminate Lease</button>
{!! Form::close(['class' => 'close-reveal-modal']) !!}  
<a class="close-reveal-modal" aria-label="Close">&#215;</a>

  <script>
  $(function() {
    $( "#datepicker" ).datepicker();
  
  });
  </script>
