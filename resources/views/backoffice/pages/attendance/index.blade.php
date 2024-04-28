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
                              <li class="breadcrumb-item active" aria-current="page">Event Attendance List</li>
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
                          </div>
                          <div class="table-responsive rounded card-table overflow-visible">
                              <table class="table border-no" id="example1">
                                  <thead>
                                      <tr>
                                          <th>#</th>
                                          <th>Event</th>
                                          <th>Schedule</th>
                                          <th>No. of Participants Attended</th>
                                          <th></th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    @forelse($events as $index => $event)
                                      <tr class="hover-primary">
                                          <td>{{$index+1}}</td>
                                          <td><strong><a href="{{ route('backoffice.events.view', $event->id) }}">{{$event->name}}</a></strong></td>
                                          <td>
                                                <strong>Start</strong> : {{$event->start?date('M d, Y @ h:i a', strtotime($event->start)):'---'}} <br>
                                                <strong>End</strong> : {{$event->end?date('M d, Y @ h:i a', strtotime($event->end)):'---'}}
                                          </td>
                                          <td>{{$event->attendance->count()}}</td>
                                          <td>
                                            <a href="{{ route('backoffice.attendance.participants', $event->id) }}" class="waves-effect waves-light btn btn-primary-light pull-right">
                                                <i data-feather="book-open"></i> &nbsp; Attendance
                                            </a>
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
@endpush
