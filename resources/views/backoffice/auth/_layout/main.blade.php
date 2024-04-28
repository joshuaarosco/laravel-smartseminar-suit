<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    {{-- <link rel="icon" href="https://rhythm-admin-template.multipurposethemes.com/images/favicon.ico"> --}}

    <title>{{config('app.name')}}</title>
	
	@include('backoffice.auth._includes.styles')

</head>
	
<body class="hold-transition theme-primary bg-img" style="background-image: url({{asset('vet-clinic/images/auth-bg/bg-1.jpg')}})">
	
	@stack('content')

	<!-- Vendor JS -->
	@include('backoffice.auth._includes.scripts')

</body>

</html>
