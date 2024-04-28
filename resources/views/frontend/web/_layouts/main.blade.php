<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>{{env('APP_NAME')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script src="{{asset('web/cdn-cgi/apps/head/8jwJmQl7fEk_9sdV6OByoscERU8.js')}}"></script>
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('images/favicons/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('images/favicons/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('images/favicons/apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('images/favicons/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('images/favicons/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('images/favicons/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('images/favicons/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('images/favicons/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/favicons/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{asset('images/favicons/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('images/favicons/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('images/favicons/manifest.json')}}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{asset('images/favicons/ms-icon-144x144.png')}}">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="description"  content=""/>
    <meta name="author"  content=""/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="auto-reply-url" content="{{ route('backoffice.auth.autoReply') }}">
    <meta name="user-id" content="{{ auth()->check()?auth()->user()->id:'0' }}">
    @include('frontend.web._includes.styles')
    @stack('css')

</head>
<body class="pace-dark">
    @include('frontend.web._components.nav',['header' => $header])
    <div class="{{$header?'p-t-60':''}}">
    @stack('content')
    </div>
    @include('frontend.web._components.footer')
    @include('frontend.web._components.search')
    @include('frontend.web._includes.scripts')
    @stack('js')
</body>
</html>
