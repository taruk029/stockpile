<!DOCTYPE html>
<html lang="en" >
<head>
<meta charset="utf-8"/>

<title>@yield('title')</title>
<meta name="description" content="Login page example"> 
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!--begin::Fonts -->

    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700%7CAsap+Condensed:500" media="all">
<script>
    WebFont.load({
        google: {"families":["Poppins:300,400,500,600,700","Asap+Condensed:500"]},
        active: function() {
            sessionStorage.fonts = true;
        }
    });
</script>
<!--end::Fonts -->
<!--begin::Page Custom Styles(used by this page) --> 
<!--begin::Page Custom Styles(used by this page) --> 
 <link href="{{ asset('public/log_in/assets/css/demo8/pages/general/login/login-1.css') }}" rel="stylesheet" type="text/css" />
<!--end::Page Custom Styles -->

<!--begin::Global Theme Styles(used by all pages) -->
<link href="{{ asset('public/log_in/assets/vendors/global/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/log_in/assets/css/demo8/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <!--end::Global Theme Styles -->

<!--begin::Layout Skins(used by all pages) -->
        <!--end::Layout Skins -->

<link rel="shortcut icon" href="public/assets/media/logos/favicon.png" />
</head>
<!-- end::Head -->

<body  style="background-color: #febd16"  class="kt-page--fluid kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-page--loading"  >
<!--     <body  style="background-image: url(asset('log_in/assets/media/demos/demo8/bg-1.jpg)') }}"  class="kt-page--fluid kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-page--loading"  > -->
 @yield('content')
<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {"colors":{"state":{"brand":"#716aca","light":"#ffffff","dark":"#282a3c","primary":"#5867dd","success":"#34bfa3","info":"#36a3f7","warning":"#ffb822","danger":"#fd3995"},"base":{"label":["#c5cbe3","#a1a8c3","#3d4465","#3e4466"],"shape":["#f0f3ff","#d9dffa","#afb4d4","#646c9a"]}}};
</script>
<!-- end::Global Config -->

<!--begin::Global Theme Bundle(used by all pages) -->
           <script src="assets/vendors/global/vendors.bundle.js" type="text/javascript"></script>
           <script src="assets/js/demo8/scripts.bundle.js" type="text/javascript"></script>
        <!--end::Global Theme Bundle -->

 

            
    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ asset('public/log_in/assets/js/demo8/pages/login/login-1.js') }}" type="text/javascript"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{{ asset('public/js/bootstrap-notify.min.js') }}"></script>
    <!--end::Global Theme Bundle -->

     <script>
        $('#flash-overlay-modal').modal();
    </script>
     @include('flash::message')
                <!--end::Page Scripts -->
    </body>
<!-- end::Body -->

<!-- Mirrored from keenthemes.com/metronic/preview/demo8/custom/pages/user/login-1.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 21 May 2019 06:01:08 GMT -->
</html>