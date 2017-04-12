<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{$title or ''}}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>


</head>

<body>

    <!-- Navigation -->
    @include('layouts.nav')

    <!-- Page Content -->
    <div class="container">
    @if (session('status'))
        <div class="alert alert-success text-center">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4>{!! session('status') !!}</h4>
        </div>
    @endif     
    @if (session('error'))
        <div class="alert alert-danger text-center">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4>{!! session('error') !!}</h4>
        </div>
    @endif 

    @yield('content')

    </div>

    @yield('modal')
    {{-- Large Modal Trigger  --}}
    <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>

    {{-- Small Modal Trigger  --}}
    <div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModal">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
        </div>
      </div>
    </div>

    
    <!-- /.container -->
    <!-- Core Javascript Vendor Files -->
    <script src="{{ asset('js/all.js') }}"></script>
    <!-- Application Specific Javascript -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
    <script>
    // Closes success alerts after 5 secs.
     window.setTimeout(function() {
        $(".alert-success").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 4000);
    </script>
</body>

</html>