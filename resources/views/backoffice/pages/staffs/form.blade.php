<div class="row">
    <div class="col-md-6">
        @include('backoffice._components.session_notif')
        <form class="form" action="" method="POST">
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title">Staff Information</h4>
            </div>
            <!-- /.box-header -->
            {{csrf_field()}}
            @if($staff)
            <input type="hidden" name="id" value="{{ $staff->id }}" class="form-control">
            <input type="hidden" name="user_id" value="{{ $staff->user_id }}" class="form-control">
            @endif
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{$errors->has('fname')?'error':null}}">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="fname" value="{{old('fname',$staff?$staff->fname:'')}}" class="form-control" placeholder="First Name">
                            @if($errors->has('fname'))
                            <div class="help-block"><ul role="alert"><li>{{$errors->first('fname')}}</li></ul></div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{$errors->has('lname')?'error':null}}">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="lname" value="{{old('lname',$staff?$staff->lname:'')}}" class="form-control" placeholder="Last Name">
                            @if($errors->has('lname'))
                            <div class="help-block"><ul role="alert"><li>{{$errors->first('lname')}}</li></ul></div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group {{$errors->has('email')?'error':null}}">
                            <label class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{old('email',$staff?$staff->email:'')}}" class="form-control" placeholder="Email">
                            @if($errors->has('email'))
                            <div class="help-block"><ul role="alert"><li>{{$errors->first('email')}}</li></ul></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <a href="{{route('backoffice.staffs.index')}}" class="btn waves-effect waves-light btn btn-warning me-1">
            <i class="ti-trash"></i> Cancel
        </a>
        <button type="submit" class="btn waves-effect waves-light btn btn-primary ">
            <i class="ti-save-alt"></i> Save
        </button>
            </form>
    </div>
    
    @if($staff)
    <div class="col-md-6">
        <div class="box no-border no-shadow">
            <div class="box-header with-border">
                <h4 class="box-title">Types</h4>
            </div>
            <div class="box-body overflow-auto">
                <!-- the events -->
                <div id="external-events">
                    <table class="table border-no" id="example1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staff as $index => $type)
                            <tr class="hover-primary">
                                <td>{{$index+1}}</td>
                                <td>{{$type->type}}</td>
                                <td>{{number_format($type->price, 2)}}</td>
                                <td>{{$type->description}}</td>
                                <td>												
                                    <div class="btn-group">
                                        <a class="hover-primary dropdown-toggle no-caret" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('backoffice.services.editType', $type->id) }}">Edit</a>
                                            <a class="dropdown-item" href="{{ route('backoffice.services.deleteType', $type->id) }}">Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="hover-primary">
                                <td colspan="5" class="text-center">No service types yet...</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="{{route('backoffice.services.addType',$staff->id)}}" class="btn waves-effect waves-light btn btn-primary">
            <i class="ti-plus"></i> Add Service Type
        </a>
    </div>
    @endif
</div>