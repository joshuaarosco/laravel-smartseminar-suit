<!DOCTYPE html>
<html>
<body>
	<h3>Hi {{$data->name}},</h3>
	<p>Please click the link below to reset your password.</p>
	<p> <a href="{{route('backoffice.auth.resetPass', $data->remember_token)}}" target="_blank">{{route('backoffice.auth.resetPass', $data->remember_token)}}</a></p>
	<p>
		Thanks,</br>
		{{config('app.name')}}
	</p>
</body>
</html>