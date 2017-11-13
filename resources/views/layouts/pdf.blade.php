<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->

	<head>
		<meta charset="utf-8"/>
		<title>Amanager</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta content="width=device-width, initial-scale=1" name="viewport"/>
		<meta content="" name="description"/>
		<meta content="" name="author"/>
		{{-- CSS Files --}}
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		{{-- These two styles fix the table thead overlapping issue when a trable
			wraps onto another page in the PDF.  Awaiting a real solution.
			https://github.com/wkhtmltopdf/wkhtmltopdf/issues/1524
		--}}
		<style>
			thead { display: table-header-group; }
			tr { page-break-inside: avoid; }
	    div.page { page-break-after: always; page-break-inside: avoid; }
		</style>
	</head>

	<body>
	
		<div id="page-wrapper-pdf">
			<div class="row">
				<div class="col-xs-12 text-center">
					<img src="/img/csstonegate.png">
				</div>
			</div>
			<div class="row">
				@yield('content')
			</div>
			<div class="row">
				<div class="col-xs-12">
					<hr>
				</div>
			</div>
		</div>

		{{-- Scripts --}}
	    <!-- Core Javascript Vendor Files -->
	    <script src="{{ asset('js/all.js') }}"></script>
	    <!-- Application Specific Javascript -->
	    <script src="{{ asset('js/app.js') }}"></script>
	    @yield('scripts')
	    <script>
	    // Tooltips
	    $('[data-toggle="tooltip"]').tooltip();
	    // Closes success alerts after 5 secs.
	     window.setTimeout(function() {
	        $(".alert-success").fadeTo(500, 0).slideUp(500, function(){
	            $(this).remove(); 
	        });
	    }, 4000);
	     // Clears Bootstrap Modals when closed
	    // $(".modal").on("hidden.bs.modal", function() {
	    //     $(this).removeData('modal');
	    // });
	    $('body').on('hidden.bs.modal', '.modal', function () {
	      $(this).removeData('bs.modal');
	    });
	    
	    </script>
	</body>
</html>