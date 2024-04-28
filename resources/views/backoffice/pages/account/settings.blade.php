<form class="form" action="{{ route('backoffice.account.save') }}" method="POST" enctype="multipart/form-data">
    {{csrf_field()}}
    @if($account)
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    @endif
    <div class="box-body">
        <h4 class="box-title text-dark mb-0"><i class="ti-user me-15"></i> Personal Information</h4>
        <hr class="my-15">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group {{$errors->has('file')?'error':null}}">
                    @if(auth()->user()->getAvatar())
                    <img src="{{asset(auth()->user()->getAvatar())}}" class="bg-lightest border-light rounded10 patient-avatar" alt="avatar" />	
                    @endif
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group {{$errors->has('file')?'error':null}}">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" name="file" class="form-control bg-white" accept="image/*">
                    @if($errors->has('file'))
                    <div class="help-block"><ul role="alert"><li>{{$errors->first('file')}}</li></ul></div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group {{$errors->has('fname')?'error':null}}">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="fname" value="{{old('fname',$account?$account->fname:'')}}" class="form-control" placeholder="First Name">
                    @if($errors->has('fname'))
                    <div class="help-block"><ul role="alert"><li>{{$errors->first('fname')}}</li></ul></div>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group {{$errors->has('lname')?'error':null}}">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="lname" value="{{old('lname',$account?$account->lname:'')}}" class="form-control" placeholder="Last Name">
                    @if($errors->has('lname'))
                    <div class="help-block"><ul role="alert"><li>{{$errors->first('lname')}}</li></ul></div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{$errors->has('contact_number')?'error':null}}">
                    <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                    <input type="tel" name="contact_number" value="{{old('contact_number',$account?$account->user->contact_number:'')}}" class="form-control" placeholder="Contact Number" data-inputmask="'mask':[ '(9999)999-9999']" data-mask>
                    @if($errors->has('contact_number'))
                    <div class="help-block"><ul role="alert"><li>{{$errors->first('contact_number')}}</li></ul></div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{$errors->has('birthdate')?'error':null}}">
                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" name="birthdate" value="{{old('birthdate',$account?$account->birthdate:'')}}" class="form-control" placeholder="Date of Birth" required>
                    @if($errors->has('birthdate'))
                    <div class="help-block"><ul role="alert"><li>{{$errors->first('birthdate')}}</li></ul></div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{$errors->has('email')?'error':null}}">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{old('email',$account?$account->user->email:'')}}" class="form-control" placeholder="Email">
                    @if($errors->has('email'))
                    <div class="help-block"><ul role="alert"><li>{{$errors->first('email')}}</li></ul></div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{$errors->has('username')?'error':null}}">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" value="{{old('username',$account?$account->user->username:'')}}" class="form-control" placeholder="Username">
                    @if($errors->has('username'))
                    <div class="help-block"><ul role="alert"><li>{{$errors->first('username')}}</li></ul></div>
                    @endif
                </div>
            </div>
        </div>
        @if(auth()->user()->type == 'vet')
        <div class="row">
            <div class="col-md-12">
                <div class="form-group {{$errors->has('specialty')?'error':null}}">
                    <label class="form-label">Specialty</label>
                    <input type="text" name="specialty" value="{{old('specialty',$account?$account->specialty:'')}}" class="form-control" placeholder="Specialty">
                    @if($errors->has('specialty'))
                    <div class="help-block"><ul role="alert"><li>{{$errors->first('specialty')}}</li></ul></div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="form-group {{$errors->has('address')?'error':null}}">
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <textarea rows="3" name="address" class="form-control" placeholder="Address">{{old('address',$account?$account->address:'')}}</textarea>
                    @if($errors->has('address'))
                    <div class="help-block"><ul role="alert"><li>{{$errors->first('address')}}</li></ul></div>
                    @endif
                </div>
            </div>
        </div>
        @if(auth()->user()->type == 'vet')
        <div class="row">
            <div class="col-md-12">
                <div class="form-group {{$errors->has('bio')?'error':null}}">
                    <label class="form-label">Bio</label>
                    <textarea rows="3" name="bio" class="form-control" placeholder="Bio">{{old('bio',$account?$account->bio:'')}}</textarea>
                    @if($errors->has('bio'))
                    <div class="help-block"><ul role="alert"><li>{{$errors->first('bio')}}</li></ul></div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn waves-effect waves-light btn btn-primary ">
                    <i class="ti-save-alt"></i> Save
                </button>
            </div>
        </div>
    </div>
</form>