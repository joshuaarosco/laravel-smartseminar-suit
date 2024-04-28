<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://rhythm-admin-template.multipurposethemes.com/images/favicon.ico">

    <title>{{config('app.name')}} - 404 Page not found </title>
  
	<!-- Vendors Style-->
	<link rel="stylesheet" href="{{asset('vet-clinic/main/css/vendors_css.css')}}">
	  
	<!-- Style-->  
	<link rel="stylesheet" href="{{asset('vet-clinic/main/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('vet-clinic/main/css/skin_color.css')}}">	

</head>
<body class="hold-transition theme-primary bg-img" style="background-image: url({{asset('vet-clinic/images/auth-bg/bg-4.jpg')}})">
	
	<section class="error-page h-p100">
		<div class="container h-p100">
		  <div class="row h-p100 align-items-center justify-content-center text-center">
			  <div class="col-lg-7 col-md-10 col-12">
				  <div class="rounded10 p-50">
					  <img src="{{asset('vet-clinic/images/auth-bg/404.jpg')}}" class="max-w-200" alt="" />
					  <h1>Page Not Found !</h1>
					  <h3>looks like, page doesn't exist</h3>
				  </div>
			  </div>				
		  </div>
		</div>
	</section>


	<!-- Vendor JS -->
	<script src="{{asset('vet-clinic/main/js/vendors.min.js')}}"></script>
	<script src="{{asset('vet-clinic/main/js/pages/chat-popup.js')}}"></script>
    <script src="{{asset('vet-clinic/assets/icons/feather-icons/feather.min.js')}}"></script>	


</body>

</html>
