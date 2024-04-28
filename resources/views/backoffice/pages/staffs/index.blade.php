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
                              <li class="breadcrumb-item active" aria-current="page">Staff List</li>
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
                      <div class="box-body overflow-auto">
                          <div class="row">
                            <div class="col-md-4">
                                <form action="" method="get">
                                <input type="text" name="search" value="{{Input::has('search')?Input::get('search'):''}}" class="form-control pull-right" placeholder="Search for a Staff...">
                                </form>
                            </div>
                            <div class="col-md-4 offset-md-4">
                                <a href="{{route('backoffice.staffs.create')}}" class="waves-effect waves-light btn btn-outline btn-primary mb-5 pull-right">Create New</a>
                            </div>
                          </div>
                          <div class="table-responsive rounded card-table overflow-visible">
                              <table class="table border-no" id="example1">
                                  <thead>
                                      <tr>
                                          <th style="width: 5%;">#</th>
                                          <th style="width: 40%;">Name</th>
                                          <th style="width: 55%;">Email</th>
                                          <!-- <th style="width: 5%;"></th> -->
                                      </tr>
                                  </thead>
                                  <tbody>
                                    @forelse($staffs as $index => $staff)
                                      <tr class="hover-primary">
                                          <td>{{$index+1}}</td>
                                          <td>{{$staff->fname}} {{$staff->lname}}</td>
                                          <td>{{$staff->user->email}}</td>
                                          {{-- <td>												
                                            <div class="btn-group">
                                                <a class="hover-primary dropdown-toggle no-caret" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                                                <div class="dropdown-menu">
                                                  <!-- <a class="dropdown-item" href="{{route('backoffice.staffs.edit', $staff->id)}}">Edit</a> -->
                                                  <a class="dropdown-item" href="{{route('backoffice.staffs.delete', $staff->id)}}">Delete</a>
                                                </div>
                                              </div>
                                          </td> --}}
                                      </tr>
                                      @empty
                                      <tr class="hover-primary">
                                        <td colspan="6" class="text-center">No {{$title}} record yet...</td>
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
<script src="{{asset('vet-clinic/main/js/pages/patients.js')}}"></script>
@endpush
