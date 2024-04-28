@extends('backoffice.auth._layout.main')

@push('content')
<div class="container h-p100">
  <div class="row align-items-center justify-content-md-center h-p100">	
    
    <div class="col-12">
      <div class="row justify-content-center g-0">
        <div class="col-lg-5 col-md-5 col-12">
          <div class="bg-white rounded10 shadow-lg">
            <div class="content-top-agile p-20 pb-0">
                <h2 class="text-primary">Reset Password</h2>	
            </div>
            <div class="p-40">
              @if(session()->has('notification-status'))
              <div class="mb-20">
                <span class="badge badge-lg badge-{{session()->get('notification-status') == 'warning'? 'danger': 'success'}} text-wrap text-start w-p100">
                  <strong>{{Str::title(session()->get('notification-status'))}}: </strong> {{session()->get('notification-msg')}}
                </span>
              </div>
              @endif
              <form action="" method="post">
                {{csrf_field()}}
                <input type="hidden" name="token" value="{{$user->remember_token}}">
                <div class="form-group {{$errors->has('new_password')?'error':null}}">
                  <div class="input-group mb-3">
                    <span class="input-group-text bg-transparent"><i class="ti-lock"></i></span>
                    <input type="password" name="new_password" class="form-control ps-15 bg-transparent" placeholder="Password">
                  </div>
                </div>
                @if($errors->has('new_password'))
                    <span class="help-block"><ul role="alert"><li>{{$errors->first('new_password')}}</li></ul></span>
                @endif
                <div class="form-group {{$errors->has('new_password_confirmation')?'error':null}}">
                  <div class="input-group mb-3">
                    <span class="input-group-text bg-transparent"><i class="ti-lock"></i></span>
                    <input type="password" name="new_password_confirmation" class="form-control ps-15 bg-transparent" placeholder="Confirm Password">
                  </div>
                </div>
                @if($errors->has('new_password_confirmation'))
                    <span class="help-block"><ul role="alert"><li>{{$errors->first('new_password_confirmation')}}</li></ul></span>
                @endif
                <div class="row">
                  <!-- /.col -->
                  <div class="col-12 text-center">
                    <button type="submit" class="btn btn-danger mt-10">UPDATE PASSWORD</button>
                  </div>
                  <!-- /.col -->
                  </div>
              </form>				
                <div class="text-center">
                    <p class="mt-15 mb-0">Already have an account?<a href="{{ route('backoffice.auth.login') }}" class="text-danger ms-5"> Sign In</a></p>
                </div>
            </div>						
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endpush
