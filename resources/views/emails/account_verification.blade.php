<!DOCTYPE html>
<html>
<body>
	<h3>Hi {{$data->name}}</h3>
	<p>Please click the link below to verify your account email information.</p>
	<p> <a href="{{route('backoffice.auth.verify', $data->username)}}" target="_blank">{{route('backoffice.auth.verify', $data->username)}}</a></p>
	<p>
		Thanks,</br>
		{{config('app.name')}}
	</p>
</body>
</html>