@extends('backoffice._layout.main')

@push('title',$title.' List')

@push('css')
<style type="text/css">
    .overflow-visible { 
        overflow: visible;
    }
</style>
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
    /* #1dbfc1 */
    .fc-event-past{
        background: #608ad2!important;
    }
    .box-past{
        background: #608ad2;
        height : 15px;
        width : 15px;
        float: left;
        margin-right: 5px;
    }
    .box-future{
        background: #1dbfc1;
        height : 15px;
        width : 15px;
        float: left;
        margin-right: 5px;
    }
    .fc-day-past {
        background-color: #c5c5c5;
    }
    .box-current-date{
        background: #fffadf;
        height : 15px;
        width : 15px;
        float: left;
        margin-right: 5px;
    }
    .box-past-date{
        background: #c5c5c5;
        height : 15px;
        width : 15px;
        float: left;
        margin-right: 5px;
    }
    .event-date{
        padding: 5px;
        border: 2px solid;
        line-height: 20px;
        border-radius: 5px;
        width: 60px;
    }
    .event-day{
        font-size: 30px;
        font-weight: 500;
    }
    .event-month{
        font-size: 15px;
        font-weight: 300;
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
                    <h4 class="page-title">Events</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('backoffice.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ config('app.name') }} Calendar</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Main content -->
        <section class="content">
            <div class="row">	
                <div class="col-xl-5 col-lg-5 col-12">
                    <div class="box">
                        <div class="box-body">
                            <div id="calendar"></div>
                            <!-- <div class="row mt-10">
                                <div class="col-md-12">LEGENDS:</div>
                                <div class="col-md-6">
                                    <div class="box-past-date"></div> Past Date
                                </div>
                                <div class="col-md-6">
                                    <div class="box-current-date"></div> Current Date
                                </div>
                                <div class="col-md-6">
                                    <div class="box-past"></div> Past Events
                                </div>
                                <div class="col-md-6">
                                    <div class="box-future"></div> Future Events
                                </div>
                            </div> -->
                        </div>
                    </div> 
                </div>
                <div class="col-xl-7 col-lg-7 col-12"> 
                    @include('backoffice._components.session_notif')
                    <div class="box no-border no-shadow">
                        <div class="box-body overflow-auto">
                            <!-- the events -->
                            <div id="external-events">
                                <h3 class="fw-300">Events & Seminars</h3>
                                <hr>
                                <table class="table border-no" id="example1">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="width: 45%">Information</th>
                                            <th style="width: 40%">Schedule</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($events as $index => $event)
                                        <tr class="hover-primary">
                                            <td>
                                                <div class="border-primary event-date bg-temple-dark text-center">
                                                    <span class="event-month">{{$event->start?date('M', strtotime($event->start)):'---'}}</span><br>
                                                    <span class="event-day">{{$event->start?date('j', strtotime($event->start)):'---'}}</span>
                                                </div>
                                            </td>
                                            <td>
                                                Event : <strong>{{$event->name}}</strong><br>
                                                Status:
                                                @if($event->status != 'Happening')
                                                <strong>{{$event->status}}</strong>
                                                @else
                                                <strong class="text-primary">{{$event->status}} Now</strong>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>Start</strong> : {{$event->start?date('M d, Y @ h:i a', strtotime($event->start)):'---'}} <br>
                                                <strong>End</strong> : {{$event->end?date('M d, Y @ h:i a', strtotime($event->end)):'---'}}
                                            </td>
                                            <td>				
                                                @if(auth()->user()->type != 'participant')								
                                                <div class="btn-group">
                                                    @if($event->status == 'Pending')
                                                    <a href="{{ route('backoffice.events.update_status',[ $event->id, 'Happening']) }}" class="waves-effect waves-light btn btn-primary-light">Mark as Happening</a>
                                                    @elseif($event->status == 'Happening')
                                                    <a href="{{ route('backoffice.events.update_status', [$event->id, 'Completed']) }}" class="waves-effect waves-light btn btn-success-light">Mark as Completed</a>
                                                    @endif
                                                    <button class="waves-effect waves-light btn btn-light no-caret" data-bs-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-h"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('backoffice.events.view', $event->id) }}">View event</a>
                                                        <a class="dropdown-item" href="{{ route('backoffice.events.edit', $event->id) }}">Edit event</a>
                                                        <a class="dropdown-item" href="{{ route('backoffice.events.cancel', $event->id) }}">Cancel event</a>
                                                    </div>
                                                </div>
                                                @else
                                                @if($event->status == 'Happening')
                                                <a href="{{ route('backoffice.events.view', $event->id) }}" class="waves-effect waves-light btn btn-success-light">
                                                    Join
                                                </a>
                                                @endif
                                                @endif
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
                    @if(auth()->check() AND !in_array(auth()->user()->type, ['participant']))
                    <a href="{{ route('backoffice.events.create') }}" class="btn btn-primary">
                        <i class="ti-calendar"></i> Create Event
                    </a>
                    @endif
                </div> 
            </div>
        </section>
        <!-- /.content -->
    </div>	  
    
</div>

@if(auth()->user()->type == 'patient')
@include('commons.chatbot')
@endif
@endpush

@push('js')
<script src="{{asset('vet-clinic/main/js/vendors.min.js')}}"></script>
<script src="{{asset('vet-clinic/main/js/pages/chat-popup.js')}}"></script>
<script src="{{asset('vet-clinic/assets/icons/feather-icons/feather.min.js')}}"></script>	
<script src="{{asset('vet-clinic/assets/vendor_components/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_components/perfect-scrollbar-master/perfect-scrollbar.jquery.min.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_components/fullcalendar/lib/moment.min.js')}}"></script>

<!-- Rhythm Admin App -->
<script src="{{asset('vet-clinic/main/js/template.js')}}"></script>
<script src="{{asset('vet-clinic/main/js/fullcalendar.min.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'Asia/Singapore',
            themeSystem: 'bootstrap5',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            dayMaxEvents: true, // allow "more" link when too many events
            events: {!! $calendar !!}
        });
        
        calendar.render();
    });
    
</script>

<script src="{{asset('pages/js/moment.js')}}"></script>
@endpush