@if( session()->has('notification-status') )
<div class="row alert-div">
    <div class="col-md-12">
        <div class="b-1 border-{{ session()->get('notification-status') }} bg-{{ session()->get('notification-status') }}-light rounded p-10 mb-15">{{ session()->get('notification-msg') }} <i class="ti-close pull-right mt-1 alert-close"></i></div>
    </div>
</div>
@endif