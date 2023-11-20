@extends('layout.storelayout')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('store') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('store.checkout') }}">Checkout</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Success</li>
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

        	<div class="error-content text-center" style="background-image: url(assets/images/backgrounds/error-bg.jpg)">
            	<div class="container">
            		<h1 class="error-title">Pesanan Berhasil</h1><!-- End .error-title -->
            		<p>Terima kasih sudah memesan produk kami, Admin akan segera menghubungi anda untuk konfirmasi pesanan.</p>
            		<a href="{{ route('store') }}" class="btn btn-outline-primary-2 btn-minwidth-lg">
            			<span>KEMBALI KE BERANDA</span>
            			<i class="icon-long-arrow-right"></i>
            		</a>
            	</div><!-- End .container -->
        	</div><!-- End .error-content text-center -->
@endsection
