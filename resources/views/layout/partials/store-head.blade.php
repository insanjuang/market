<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Adin Mart - Store</title>
<meta name="keywords" content="toko,jualan,sembako">
<meta name="description" content="Adin Mart - Toko Serba Ada">
<meta name="author" content="insan-juang-sejahtera">
<!-- Favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="{{url('assets/img/favicon.png')}}">
<link rel="icon" type="image/png" sizes="32x32" href="{{url('assets/img/favicon.png')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{url('assets/img/favicon.png')}}">
<link rel="shortcut icon" href="{{url('assets/img/favicon.png')}}">
<meta name="apple-mobile-web-app-title" content="Adin-Mart">
<meta name="application-name" content="Adin-Mart">
<meta name="theme-color" content="#ffffff">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
<!-- Plugins CSS File -->
<link rel="stylesheet" href="{{url('assets/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{url('assets/plugins/owlcarousel/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{url('assets/plugins/magnific-popup/magnific-popup.css')}}">
<link rel="stylesheet" href="{{url('assets/plugins/nouislider/nouislider.css')}}">

{{-- MapBox Plugins --}}
{{-- <link href="https://api.mapbox.com/mapbox-assembly/v1.3.0/assembly.min.css" rel="stylesheet">
<script id="search-js" defer="" src="https://api.mapbox.com/search-js/v1.0.0-beta.17/web.js"></script>
<script src="{{ URL::asset('/assets/plugins/mapbox-gl-geocoder/mapbox-gl-geocoder.min.js') }}"></script>
<link href="{{url('assets/plugins/mapbox-gl-geocoder/mapbox-gl-geocoder.css')}}" rel='stylesheet' /> --}}



<!-- Main CSS File -->
<link rel="stylesheet" href="{{url('assets/css/store-style.css')}}">
@if (Route::is(['store']))
<link rel="stylesheet" href="{{url('assets/css/store-skin.css')}}">
<link rel="stylesheet" href="{{url('assets/css/store.css')}}">
@endif
