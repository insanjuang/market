<!DOCTYPE html>
<html lang="en">
<head>
     @include('layout.partials.store-head')
</head>

<body>
    <div class="page-wrapper">
        @include('layout.partials.store-header')

        <main class="main">
            @yield('content')
        </main><!-- End .main -->

        @include('layout.partials.store-footer')
    </div><!-- End .page-wrapper -->
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>

    @include('layout.partials.store-footscripts')
</body>

</html>
