<!DOCTYPE html>
<html>
<body>
	<h3>Hi {{$data->name}}</h3>
	<p>Welcome to {{config('app.name')}}!</br></br> Below are your credentials, please login  here <a href="{{route('backoffice.auth.login')}}" target="_blank">{{route('backoffice.auth.login')}}</a> and update your password.</p>
	<p>Username: <strong>{{$data->username}}</strong> </br>
	   Password: <strong>{{$data->password}}</strong>
	</p>
	<p>
		Thanks,</br>
		{{config('app.name')}}
	</p>
</body>
</html>