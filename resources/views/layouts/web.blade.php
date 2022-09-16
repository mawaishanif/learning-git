<html>
    <head>
    {{--<title>App Name - @yield('title')</title>--}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('shopify-app.app_name') }}</title>
    @include('partials/styles')
    @yield('styles')
</head>

<body id="page-top">
    <div id="wrapper">
        @if(Request::path() != "signup")
    <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
        <div class="container-fluid d-flex flex-column p-0">
            
                @include('partials/sidebar')
        
            <div class="text-center d-none d-md-inline">
                <button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button>
            </div>
        </div>
    </nav>
        @endif
    <div class="d-flex flex-column content_width" id="content-wrapper">
        <div id="content"> 
             @yield('content')
        </div>
    </div>
    <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    @if(config('shopify-app.appbridge_enabled'))
    <script src="https://unpkg.com/@shopify/app-bridge"></script>
    <script type="text/javascript">
        var AppBridge = window['app-bridge'];
        var createApp = AppBridge.default;
        var actions = AppBridge.actions;
        var TitleBar = actions.TitleBar;
        var Button = actions.Button;
        var Redirect = actions.Redirect;
        var titleBarOptions = {
            title: 'Welcome',
        };
        var app = createApp({
            apiKey: '{{ config('shopify-app.api_key') }}',
            shopOrigin: '{{ auth()->user()->name }}',
            forceRedirect: true,
        });
        var myTitleBar = TitleBar.create(app, titleBarOptions);
    </script>
    @endif
    @include('partials/scripts')
    @yield('scripts')
    </body>
</html>