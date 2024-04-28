<!DOCTYPE html>
<html>
<body>
	@if($data->patient_id)
	<h3>Hello {{$data->patient->user->name}},</h3>
	@else
	<h3>Hello {{$data->name}},</h3>
	@endif
	<p>Your Appointment Request for {{ucfirst($data->service->name)}} has been scheduled on {{ date('M d, Y @ h:i a', strtotime($data->start)) }}.</p>
	<p>Please <a href="{{route('backoffice.auth.login')}}" target="_blank">login</a> for more details.</p>
	<p>
		Thanks,</br>
		{{config('app.name')}}
	</p>
</body>
</html>