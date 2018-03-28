@extends('layouts.layout')

@section('content')
        <div class="row">
            <div class="col-lg-12">
                <h1>Activity Log</h1>
                @foreach($activity->groupBy('causer.firstname') as $k => $activities)
                <h3>{{$k}}</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-condensed  table-hover responsive" id="vacantApartments" width="100%">
                    <thead>
                    <tr>
                        <th align="center" style="cursor:pointer">Record</th>
                        <th align="center" style="cursor:pointer" class="text-center">Activity</th>
                        <th align="center" style="cursor:pointer" class="text-center">Date</th>
                    </tr>
                    </thead>
                    <tbody>

                            @forelse($activities as $a)
                                <tr>
                                    <td>{{ $a->subject_type }} ID: {{$a->subject_id}}</td>
                                    <td class="text-center">{{$a->description}}</td> 
                                    <td>{{$a->created_at}}</td>                           
                                </tr>       
                            @empty
                                <tr>
                                    <td colspan="6">None Added</td>
                                </tr>
                            @endforelse

                    </tbody>
                    </table>
                </div>
                @endforeach

                
            </div>
        </div>
        <!-- /.row -->
@stop