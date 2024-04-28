@extends('backoffice._layout.main')

@push('title','Appointment Request')

@push('css')
<style>
    .start-date-error{
        display: none;
    }
    .end-date-error{
        display: none;
    }
    .input-select{
        background-color: white!important;
    }
    .form-control:disabled, .form-control:read-only {
        background-color: #ffffff;
        opacity: 1;
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
                                <li class="breadcrumb-item active" aria-current="page">Create Event</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    @include('backoffice._components.session_notif')
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">Create Event</h4>
                        </div>
                        <!-- /.box-header -->
                        @include('backoffice.pages.events.form')
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endpush

@push('js')
<script src="{{asset('vet-clinic/main/js/vendors.min.js')}}"></script>
<script src="{{asset('vet-clinic/main/js/pages/chat-popup.js')}}"></script>
<script src="{{asset('vet-clinic/assets/icons/feather-icons/feather.min.js')}}"></script>	
<script src="{{asset('vet-clinic/assets/vendor_components/bootstrap-select/dist/js/bootstrap-select.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_components/select2/dist/js/select2.full.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_plugins/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_components/moment/min/moment.min.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_plugins/iCheck/icheck.min.js')}}"></script>

<!-- Rhythm Admin App -->
<script src="{{asset('vet-clinic/assets/vendor_components/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('vet-clinic/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js')}}"></script>
<script src="{{asset('vet-clinic/main/js/template.js')}}"></script>
<script src="{{asset('vet-clinic/main/js/pages/editor.js')}}"></script>
<script src="{{asset('vet-clinic/main/js/pages/advanced-form-element.js')}}"></script>

<script>

function checkTimeRange(input, className) {
    console.log(input);
    // Get the selected date and time from the input
    const selectedDateTime = new Date(input.value);
    
    // Define the start and end times (in this case, 8am and 5pm)
    const startTime = new Date(selectedDateTime);
    startTime.setHours(8, 0, 0, 0); // 8am

    const endTime = new Date(selectedDateTime);
    endTime.setHours(17, 0, 0, 0); // 5pm

    // Check if the selected time is within the allowed range
    if (selectedDateTime < startTime || selectedDateTime > endTime) {
        $("."+className).addClass('error');
        alert('Please select a time between 8am and 5pm.');
        input.value = ''; // Reset the input value
        $('.'+className+"-error").css('display', 'block');
    }else{
        $('.'+className+"-error").css('display', 'none');
    }
}

$(function() {
    // $(".input-service-type").prop("disabled", true);
    // var today = new Date().toISOString().slice(0, 16);

    const currentDate = new Date();

    var year = currentDate.getFullYear();
    var month = currentDate.getMonth()+1;
    var dt = currentDate.getDate();

    if (dt < 10) {
        dt = '0' + dt;
    }
    if (month < 10) {
        month = '0' + month;
    }

    var today = year+'-' + month + '-'+dt;

    // document.getElementsById("start")[0].min = today;
    // document.getElementsById("end")[0].min = today;

    $("#start").on("change", function(){
        var val = $(this).val();

        var input = document.getElementById("end");
        input.setAttribute("min", val);

        var start_time = document.getElementById('start-time'), time, i;
        if(val == "{!! date('Y-m-d') !!}"){
            for(i = 0; i < start_time.length; i++) {
                time = start_time[i];
                if(time.value < {!! date('H') !!}){
                    time.disabled = true;
                }
            }
        }else{
            for(i = 0; i < start_time.length; i++) {
                time = start_time[i];
                time.disabled = false;
            }
        }
    });
    
    $("#end").on("change", function(){
        var val = $(this).val();
        var end_time = document.getElementById('end-time'), time, i;
        if(val == "{!! date('Y-m-d') !!}"){
            for(i = 0; i < end_time.length; i++) {
                time = end_time[i];
                if(time.value < {!! date('H') !!}){
                    time.disabled = true;
                }
            }
        }else{
            for(i = 0; i < end_time.length; i++) {
                time = end_time[i];
                time.disabled = false;
            }
        }
    });
});
</script>
@endpush