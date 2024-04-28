<form class="form" action="" method="POST">
    {{csrf_field()}}
    @if($event)
    <input type="hidden" name="id" value="{{ $event->id }}" class="form-control">
    <input type="hidden" name="details" value="{{ $event->details }}" class="form-control">
    @endif
    <div class="box-body">
        <div class="row">
            <div class="form-group {{$errors->has('name')?'error':null}}">
                <label class="form-label">Event Title <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{old('name',$event?$event->name:'')}}" class="form-control" placeholder="Event Title">
                @if($errors->has('name'))
                <div class="help-block"><ul role="alert"><li>{{$errors->first('name')}}</li></ul></div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="form-group {{$errors->has('category_id')?'error':null}}">
                <label class="form-label">Event Category <span class="text-danger">*</span></label>
                <select name="category_id" class="form-control">
                    <option value="">--Choose A Category--</option>
                    @foreach($categories as $index => $category)
                    @if($category == old('category_id') OR ($event?$event->category_id:''))
                    <option value="{{ $index }}" selected>{{ $category }}</option>
                    @else
                    <option value="{{ $index }}">{{ $category }}</option>
                    @endif
                    @endforeach
                </select>
                @if($errors->has('category_id'))
                <div class="help-block"><ul role="alert"><li>{{$errors->first('category_id')}}</li></ul></div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="form-group {{$errors->has('details')?'error':null}}">
                <label class="form-label">Details <span class="text-danger">*</span></label>
                <textarea rows="3" name="details" id="editor1" class="textarea" style="width: 100%; height: 500px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" placeholder="Event Details">{{old('details',$event?$event->details:'')}}</textarea>
                @if($errors->has('details'))
                <div class="help-block"><ul role="alert"><li>{{$errors->first('details')}}</li></ul></div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{$errors->has('start')?'error':null}} start-date">
                    <label class="form-label">Start <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start" min="{{date('Y-m-d')}}" id="start" value="{{old('start',$event?date('Y-m-d\TH:i', strtotime($event->start)):'')}}" class="form-control" placeholder="Date">
                    @if($errors->has('start'))
                    <div class="help-block"><ul role="alert"><li>{{$errors->first('start')}}</li></ul></div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{$errors->has('end')?'error':null}} end-date">
                    <label class="form-label">End <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="end" min="{{date('Y-m-d')}}" id="end" value="{{old('end',$event?date('Y-m-d\TH:i',strtotime($event->end)):'')}}" class="form-control" placeholder="End Date">
                    @if($errors->has('end'))
                    <div class="help-block"><ul role="alert"><li>{{$errors->first('end')}}</li></ul></div>
                    @endif
                </div>
            </div>
        </div>
        @if(auth()->user()->type != 'patient')
        <div class="row">
            <div class="col-md-12">
                <div class="form-group {{$errors->has('status')?'error':null}}">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control bg-white input-pet">
                        <option value="">---</option>
                        @foreach($statuses as $index => $status)
                        @if($event AND $event->status == $status OR old('status') == $index)
                        <option value="{{ $status }}" selected>{{ $status }}</option>
                        @else
                        <option value="{{ $status }}">{{ $status }}</option>
                        @endif
                        @endforeach
                    </select>
                    @if($errors->has('status'))
                    <div class="help-block"><ul role="alert"><li>{{$errors->first('status')}}</li></ul></div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-end">
        <a href="{{route('backoffice.events.index')}}" class="btn waves-effect waves-light btn btn-outline btn-warning me-1">
            <i class="fa fa-sign-out" style="font-size:24px"></i> Exit
        </a>
        <button type="submit" class="btn waves-effect waves-light btn btn-outline btn-primary ">
            <i class="ti-save-alt"></i> Save
        </button>
    </div>  
</form>