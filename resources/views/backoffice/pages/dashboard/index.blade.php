@extends('backoffice._layout.main')

@push('title','Dashboard')

@push('css')
<link class="main-stylesheet" href="{{asset('pages/css/chat.css')}}" rel="stylesheet" type="text/css" />
<style>
    .col-xs-2 {
        width: 16.66666667%;
    }
    .col-xs-6 {
        width: 50%;
    }
    .col-xs-10 {
        width: 83.33333333%;
    }
    .modal-content{
        max-width: 750px!important;
    }
    .feather-50{
        width: 50px;
        height: 50px;
    }
    .feather-16{
        width: 16px;
        height: 16px;
    }
</style>
@endpush

@push('content')
 
 <div class="content-wrapper">
    <div class="container-full">
      <!-- Main content -->
      <section class="content">
          <div class="row">
            @if(auth()->user()->type != 'participant')
            <div class="col-xl-6">
                <div class="row">
                <div class="col-xl-3 col-md-6 col-6">
                    <div class="box">
                        <div class="box-body text-center">
                            <div class="bg-primary-light rounded10 p-20 mx-auto w-100 h-100">
                                <h1 class="text-default"><i data-feather="calendar" class="text-dark feather-50"></i></h1>
                            </div>
                            <p class="text-fade mt-15 mb-5">Total Events</p>
                            <h2 class="mt-0">{{ $eventCount }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-6">
                    <div class="box">
                        <div class="box-body text-center">
                            <div class="bg-danger-light rounded10 p-20 mx-auto w-100 h-100">
                                <h1 class="text-default"><i data-feather="message-square" class="text-dark feather-50"></i></h1>
                            </div>
                            <p class="text-fade mt-15 mb-5">Feedbacks</p>
                            <h2 class="mt-0">{{ $feedbackCount }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-6">
                    <div class="box">
                        <div class="box-body text-center">
                            <div class="bg-warning-light rounded10 p-20 mx-auto w-100 h-100">
                                <h1 class="text-default"><i data-feather="award" class="text-dark feather-50"></i></h1>
                            </div>
                            <p class="text-fade mt-15 mb-5">Certifications</p>
                            <h2 class="mt-0">132</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-6">
                    <div class="box">
                        <div class="box-body text-center">
                            <div class="bg-info-light rounded10 p-20 mx-auto w-100 h-100">
                                <h1 class="text-default"><i data-feather="users" class="text-dark feather-50"></i></h1>
                            </div>
                            <p class="text-fade mt-15 mb-5">Partipants</p>
                            <h2 class="mt-0">{{ $participantCount }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-6">
                    <div class="box">
                        <div class="box-body text-center">
                            <div class="bg-success-light rounded10 p-20 mx-auto w-100 h-100">
                                <h1 class="text-default"><i data-feather="users" class="text-dark feather-50"></i></h1>
                            </div>
                            <p class="text-fade mt-15 mb-5">Staff</p>
                            <h2 class="mt-0">{{ $staffCount }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-md-6 col-sm-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title fw-300">Staff List</h3>
                            @if(in_array(auth()->user()->type, ['admin', 'super_user']))
                            <a class="mb-0 pull-right" href="{{ route('backoffice.staffs.index') }}"><i data-feather="plus-circle" class="text-dark"></i></a>
                            @endif
                        </div>
                        <div class="box-body">
                            <div class="inner-user-div3">
                                @forelse($staffs as $index => $staff)
                                <div class="d-flex align-items-center mb-30">
                                    <div class="me-15 avatar avatar-lg ">
                                        <img src="{{ asset($staff->getAvatar()) }}" class="bg-primary-light" alt="{{ $staff->user->name }}">
                                    </div>
                                    <div class="d-flex flex-column flex-grow-1 fw-500">
                                        <a href="{{ route('backoffice.staffs.view', $staff->id) }}" class="text-dark hover-warning mb-1 fs-16">{{ $staff->user->name }}</a>
                                        <span class="text-fade">{{ $staff->user->email }}</span>
                                    </div>
                                    <div class="dropdown">
                                        <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{ route('backoffice.staffs.view', $staff->id) }}">View</a>
                                            <!-- <a class="dropdown-item" href="{{ route('backoffice.staffs.view', $staff->id) }}">Deactivate</a> -->
                                        </div>
                                    </div>
                                </div>
                                @empty
                                @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	
            </div>
            @endif
            <div class="col-xl-6">
                @if(auth()->user()->type == 'participant')
                <div class="row">
                    <div class="col-xl-6 col-md-6 col-6">
                        <div class="box">
                            <div class="box-body text-center">
                                <div class="bg-warning-light rounded10 p-20 mx-auto w-100 h-100">
                                    <h1 class="text-default"><i data-feather="award" class="text-dark feather-50"></i></h1>
                                </div>
                                <p class="text-fade mt-15 mb-5">Certificates</p>
                                <h2 class="mt-0">{{ $eventCount }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-6">
                        <div class="box">
                            <div class="box-body text-center">
                                <div class="bg-primary-light rounded10 p-20 mx-auto w-100 h-100">
                                    <h1 class="text-default"><i data-feather="calendar" class="text-dark feather-50"></i></h1>
                                </div>
                                <p class="text-fade mt-15 mb-5">Events & Seminars Attended</p>
                                <h2 class="mt-0">{{ $attendedCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="box no-border no-shadow">
                            <div class="box-body overflow-auto">
                                <!-- the events -->
                                <div id="external-events">
                                    <h3 class="fw-300">Events & Seminars</h3>
                                    <hr>
                                    <table class="table border-no" id="example1">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Information</th>
                                                <th>Status</th>
                                                <th>Schedule</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($events as $index => $event)
                                            <tr class="hover-primary">
                                                <td>{{$index+1}}</td>
                                                <td>
                                                    <strong>Event</strong> : {{$event->name}}
                                                <td>{{$event->status}}</td>
                                                <td>
                                                    <strong>Start</strong> : {{$event->start?date('M d, Y @ h:i a', strtotime($event->start)):'---'}} <br>
                                                    <strong>End</strong> : {{$event->end?date('M d, Y @ h:i a', strtotime($event->end)):'---'}}
                                                </td>
                                                <td>
                                                    <a href="{{ route('backoffice.events.view', $event->id) }}" class="waves-effect waves-light btn btn-primary-light">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr class="hover-primary">
                                                <td colspan="4" class="text-center">No Events yet...</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('backoffice.events.index') }}" class="btn btn-primary">
                            Go to Events <i class="ti-arrow-right"></i>
                        </a>
                    </div>
                </div>	
            </div>
          </div>	
      </section>
      <!-- /.content -->
    </div>
</div>

<!-- /.content-wrapper -->
@if(auth()->user()->type == 'patient')
@include('commons.chatbot')
@endif
@endpush

@push('js')
<!-- Vendor JS -->
<script src="{{asset('vet-clinic/main/js/vendors.min.js')}}"></script>
<script src="{{asset('vet-clinic/main/js/pages/chat-popup.js')}}"></script>
<script src="{{asset('vet-clinic/assets/icons/feather-icons/feather.min.js')}}"></script>

<script src="{{asset('vet-clinic/assets/vendor_components/date-paginator/moment.min.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_components/date-paginator/bootstrap-datepaginator.min.js')}}"></script>

<!-- Rhythm Admin App -->
<script src="{{asset('vet-clinic/main/js/template.js')}}"></script>
<script src="{{asset('vet-clinic/main/js/pages/dashboard.js')}}"></script>

<script src="{{asset('pages/js/moment.js')}}"></script>
<script src="{{asset('pages/js/chat.js')}}"></script>
<script>
</script>
@endpush
