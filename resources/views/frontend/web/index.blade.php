@extends('frontend.web._layouts.main',['header' => false])

@push('content')
@include('frontend.web._sections.banner')
<!-- @include('frontend.web._sections.intro') -->
@endpush
@push('js')
@endpush

@push('css')
<link class="main-stylesheet" href="{{asset('pages/css/chat.css')}}" rel="stylesheet" type="text/css" />
@endpush
