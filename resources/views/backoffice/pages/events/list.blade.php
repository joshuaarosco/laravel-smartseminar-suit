@extends('backoffice._layout.main')

@push('title',$title.' List')

@push('css')
    <style type="text/css">
        .overflow-visible { 
            overflow: visible;
        }
    </style>
@endpush

@push('content')
<div class="content-wrapper">
    <div class="container-full">
      <!-- Content Header (Page header) -->	  
      <div class="content-header">
          <div class="d-flex align-items-center">
              <div class="me-auto">
                  <h4 class="page-title">{{$title}}</h4>
                  <div class="d-inline-block align-items-center">
                      <nav>
                          <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{route('backoffice.index')}}"><i class="mdi mdi-home-outline"></i></a></li>
                              <li class="breadcrumb-item active" aria-current="page">Event List</li>
                          </ol>
                      </nav>
                  </div>
              </div>
              
          </div>
      </div>
        
      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-12">
                    @include('backoffice._components.session_notif')
                  <div class="box">
                      <div class="box-body">
                          <div class="row">
                            <div class="col-md-4">
                                <form action="" method="get">
                                <input type="text" name="search" value="{{Input::has('search')?Input::get('search'):''}}" class="form-control pull-right" placeholder="Search for an Event...">
                                </form>
                            </div>
                            @if(auth()->user()->type != 'participant')
                            <div class="col-md-4 offset-md-4">
                                <a href="{{route('backoffice.events.create')}}" class="waves-effect waves-light btn btn-outline btn-primary mb-5 pull-right">Create New</a>
                            </div>
                            @endif
                          </div>
                          <div class="table-responsive rounded card-table overflow-visible">
                              <table class="table border-no" id="example1">
                                  <thead>
                                      <tr>
                                          <th>#</th>
                                          <th>Title</th>
                                          <th>Schedule</th>
                                          <th>Status</th>
                                          <th></th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    @forelse($events as $index => $event)
                                      <tr class="hover-primary">
                                          <td>{{$index+1}}</td>
                                          <td>{{$event->name}}</td>
                                          <td>
                                            <strong>Start</strong> : {{$event->start?date('M d, Y @ h:i a', strtotime($event->start)):'---'}} <br>
                                            <strong>End</strong> : {{$event->end?date('M d, Y @ h:i a', strtotime($event->end)):'---'}}
                                          </td>
                                          <td>{{$event->status}}</td>
                                          <td>												
                                              <div class="btn-group pull-right">
                                                @if(auth()->user()->type == 'participant')
                                                <!-- {{ auth()->user()->myCertificate($event->id) }} -->
                                                @if(auth()->user()->myCertificate($event->id))
                                                <a href="{{ route('backoffice.certificates.view', auth()->user()->myCertificate($event->id)->certificate_id) }}" target="_blank" class="waves-effect waves-light btn btn-warning-light"><i data-feather="award"></i>&nbsp; View Certificate</a>
                                                @endif
                                                <a href="{{ route('backoffice.events.view', $event->id) }}" class="waves-effect waves-light btn btn-primary-light"><i data-feather="message-square"></i>&nbsp; Give Feedback</a>
                                                @endif
                                                @if($event->status == 'Pending')
                                                <a href="{{ route('backoffice.events.update_status',[ $event->id, 'Happening']) }}" class="waves-effect waves-light btn btn-primary-light"><i data-feather="cast"></i>&nbsp; Mark as Happening</a>
                                                @elseif($event->status == 'Happening')
                                                <a href="{{ route('backoffice.events.update_status', [$event->id, 'Completed']) }}" class="waves-effect waves-light btn btn-success-light"><i data-feather="check"></i>&nbsp; Mark as Completed</a>
                                                @elseif($event->status == 'Completed' AND auth()->user()->type != 'participant')
                                                <a href="{{ route('backoffice.events.generate_certificate', $event->id) }}" target="_blank" class="waves-effect waves-light btn btn-warning-light"><i data-feather="award"></i>&nbsp; Generate Certificate</a>
                                                @endif
                                                <a class="waves-effect waves-light btn btn-light no-caret" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('backoffice.events.view', $event->id) }}">View event</a>
                                                    @if(auth()->user()->type != 'participant')
                                                    <a class="dropdown-item" href="{{ route('backoffice.events.edit', $event->id) }}">Edit event</a>
                                                    <a class="dropdown-item" href="{{ route('backoffice.events.cancel', $event->id) }}">Cancel event</a>
                                                    @endif
                                                </div>
                                              </div>
                                          </td>
                                      </tr>
                                      @empty
                                      <tr class="hover-primary">
                                        <td colspan="7" class="text-center">No {{$title}} record yet...</td>
                                      </tr>
                                      @endforelse
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>
          </div>			
      </section>
      <!-- /.content -->
    </div>
</div>

@endpush

@push('js')
<script src="{{asset('vet-clinic/main/js/vendors.min.js')}}"></script>
<script src="{{asset('vet-clinic/main/js/pages/chat-popup.js')}}"></script>
<script src="{{asset('vet-clinic/assets/icons/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vet-clinic/main/js/template.js')}}"></script>
<script src="{{asset('vet-clinic/main/js/pages/events.js')}}"></script>
@endpush
