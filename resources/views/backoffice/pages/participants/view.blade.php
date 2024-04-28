@extends('backoffice._layout.main')

@push('title',$title.' Details')

@push('css')
<style>
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
                  <h4 class="page-title">Participant</h4>
                  <div class="d-inline-block align-items-center">
                      <nav>
                          <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('backoffice.index')}}"><i class="mdi mdi-home-outline"></i></a></li>
                            @if(in_array(auth()->user()->type,['super_user','admin']))
                            <li class="breadcrumb-item"><a href="{{route('backoffice.participants.index')}}"><i class="mdi mdi-account-outline"></i></a></li>
                            @endif
                            <li class="breadcrumb-item active" aria-current="page">Participant Details</li>
                          </ol>
                      </nav>
                  </div>
              </div>
              
          </div>
      </div>  

      <!-- Main content -->
      <section class="content">

          <div class="row">
              <div class="col-xl-6 col-12">
                  <div class="box">
                      <div class="box-body text-end min-h-150" style="background-image:url({{asset('images/bg-hero.jpg')}}); background-repeat: no-repeat; background-position: center;background-size: cover;">	
                      </div>						
                      <div class="box-body wed-up position-relative">
                          <div class="d-md-flex align-items-center">
                              <div class=" me-20 text-center text-md-start">
                                  @if($participant->getAvatar())
                                  <img src="{{asset($participant->getAvatar())}}" class="bg-lightest border-light rounded10 patient-avatar" alt="avatar" />	
                                  @endif
                              </div>
                              <div class="mt-40">
                                  <h4 class="fw-600 mb-5">&nbsp;</h4>
                                  <h2 class="fw-300 mb-5 mt-10">{{$participant->fname}} {{$participant->mname}} {{$participant->lname}}</h2>
                              </div>
                          </div>
                      </div>				
                  </div>
                  <div class="row">
                      <div class="col-xl-12 col-12">
                          <div class="box">
                              <div class="box-body box-profile">            
                                <div class="row">
                                  <div class="col-12">
                                      <div>
                                          <p><strong>Age</strong> :<span class="text-gray ps-10">{{ $participant->age }}</span></p>
                                          <p><strong>Gender</strong> :<span class="text-gray ps-10">{{ ucfirst($participant->gender) }}</span></p>
                                          <p><strong>Email</strong> :<span class="text-gray ps-10">{{ $participant->user->email }}</span> </p>
                                          <p><strong>Phone</strong> :<span class="text-gray ps-10">{{ $participant->user->contact_number }}</span></p>
                                          <p><strong>Address</strong> :<span class="text-gray ps-10">{{ $participant->address }}</span></p>
                                      </div>
                                  </div>
                                </div>
                              </div>
                              <!-- /.box-body -->
                            </div>
                      </div>
                  </div>					
              </div>
              
              <div class="col-xl-6 col-lg-6 col-12"> 
                    @include('backoffice._components.session_notif')
                    <div class="box no-border no-shadow">
                        <div class="box-body overflow-auto">
                            <!-- the events -->
                            <div id="external-events">
                                <h3 class="fw-300">Events & Seminars Attended</h3>
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
                </div> 
          </div>

      </section>
      <!-- /.content -->
    </div>
</div>

@if(auth()->user()->type == 'participant')
@include('commons.chatbot')
@endif

@endpush

@push('js')
<script src="{{asset('vet-clinic/main/js/vendors.min.js')}}"></script>
<script src="{{asset('vet-clinic/main/js/pages/chat-popup.js')}}"></script>
<script src="{{asset('vet-clinic/assets/icons/feather-icons/feather.min.js')}}"></script>	

<script src="{{asset('vet-clinic/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js')}}"></script>	

<!-- Rhythm Admin App -->
<script src="{{asset('vet-clinic/main/js/template.js')}}"></script>
<script src="{{asset('vet-clinic/main/js/pages/participant-details.js')}}"></script>

<script src="{{asset('pages/js/moment.js')}}"></script>
<script src="{{asset('pages/js/chat.js')}}"></script>
<script type="module" src="{{asset('pages/js/firebase-chat.js')}}"></script>
@endpush