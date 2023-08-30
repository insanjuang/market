@extends('layout.storelayout')
@section('content')
    @component('pages.store.component.slider')
    @endcomponent

    <div class="container">
        <h2 class="title text-center mb-4">Kategori</h2><!-- End .title text-center -->

        <div class="cat-blocks-container">
            <div class="row">
                @foreach ($categories as $category)
                    <div class="col-6 col-sm-4 col-lg-2">
                        <a href="{{route('store.product',['category'=>$category->kode])}}" class="cat-block">
                            <figure>
                                <span>
                                    <img src="{{($category->image == "") ? URL::asset('assets/img/product/product1.jpg') : URL::asset('/storage/uploads/image/product/category/'.$category->image)}}" alt="Category image">
                                </span>
                            </figure>

                            <h3 class="cat-block-title">{{ $category->nama_kategori }}</h3><!-- End .cat-block-title -->
                        </a>
                    </div><!-- End .col-sm-4 col-lg-2 -->
                @endforeach

                <div class="col-6 col-sm-4 col-lg-2">
                    <a href="{{route('store.product',['category'=>'all'])}}" class="cat-block">
                        <figure>
                            <span>
                                <img src="{{URL::asset('assets/img/product/product1.jpg')}}" alt="Category image">
                            </span>
                        </figure>

                        <h3 class="cat-block-title">Semua Produk</h3><!-- End .cat-block-title -->
                    </a>
                </div><!-- End .col-sm-4 col-lg-2 -->
            </div><!-- End .row -->
        </div><!-- End .cat-blocks-container -->
    </div><!-- End .container -->

    <div class="mb-4"></div><!-- End .mb-4 -->

    @component('pages.store.component.banner')
    @endcomponent

    <div class="mb-3"></div><!-- End .mb-5 -->
{{--
    <pre>
        {{print_r($categoryWithNewProduct)}}
    </pre> --}}
    <div class="container new-arrivals">
        <div class="heading heading-flex mb-3">
            <div class="heading-left">
                <h2 class="title">Produk Baru</h2><!-- End .title -->
            </div><!-- End .heading-left -->

            <div class="heading-right">
                <ul class="nav nav-pills nav-border-anim justify-content-center" id="newProductTab" role="tablist">
                    @foreach ($categoryWithNewProduct as $key => $category)
                        <li class="nav-item">
                            <a class="nav-link {{($key==0)?'active':''}}" id="new-{{$category->kode}}-link" data-toggle="tab" href="#new-{{$category->kode}}-tab" role="tab"
                                aria-controls="new-{{$category->kode}}-tab" aria-selected="{{($key==0)?'true':'false'}}">{{$category->nama_kategori}}</a>
                        </li>
                    @endforeach
                </ul>
            </div><!-- End .heading-right -->
        </div><!-- End .heading -->

        <div class="tab-content tab-content-carousel just-action-icons-sm">
            @foreach ($categoryWithNewProduct as $key => $category)
            <div class="tab-pane p-0 fade {{($key==0)?'show active': ''}}" id="new-{{$category->kode}}-tab" role="tabpanel" aria-labelledby="new-{{$category->kode}}-link">
                <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl"
                    data-owl-options='{
                                    "nav": true,
                                    "dots": true,
                                    "margin": 20,
                                    "loop": false,
                                    "responsive": {
                                        "0": {
                                            "items":2
                                        },
                                        "480": {
                                            "items":2
                                        },
                                        "768": {
                                            "items":3
                                        },
                                        "992": {
                                            "items":4
                                        },
                                        "1200": {
                                            "items":5
                                        }
                                    }
                                }'>
                        @foreach ($category->newProduct as $product)
                            <div class="product product-2">
                                <figure class="product-media">
                                    <span class="product-label label-circle label-new">New</span>
                                    <a href="product.html">
                                        <img src="{{(count($product->images) > 0) ? URL::asset('storage/'.$product->images[0]->url) : URL::asset('assets/img/product/product1.jpg')}}" alt="Product image"
                                            class="product-image">
                                    </a>
                                    <div class="product-action-vertical">
                                        <a href="#" class="btn-product-icon btn-wishlist" title="Add to wishlist"></a>
                                    </div><!-- End .product-action -->
                                    <div class="product-action">
                                        <a href="#" class="btn-product btn-cart" title="Add to cart" onclick="addToCart({{$product->id_produk}})"><span>masuk keranjang</span></a>
                                        <a href="{{route('store.productDetail',$product->id_produk)}}" class="btn-product btn-quickview"
                                            title="Quick view"><span>lihat produk</span></a>
                                    </div><!-- End .product-action -->
                                </figure><!-- End .product-media -->
                                <div class="product-body">
                                    <div class="product-cat">
                                        <a href="#">{{$category->nama_kategori}}</a>
                                    </div><!-- End .product-cat -->
                                    <h3 class="product-title"><a href="{{route('store.productDetail',$product->id_produk)}}">{{$product->nama_produk}}
                                        </a></h3><!-- End .product-title -->
                                    <div class="product-price">
                                        Rp.{{$product->harga_jual}}
                                    </div><!-- End .product-price -->
                                </div><!-- End .product-body -->
                            </div><!-- End .product -->
                        @endforeach
                </div><!-- End .owl-carousel -->
            </div><!-- .End .tab-pane -->
            @endforeach
        </div><!-- End .tab-content -->
    </div><!-- End .container -->


    <div class="mb-6"></div><!-- End .mb-6 -->

    <div class="container">
        <div class="more-container text-center mt-1 mb-5">
            <a href="{{route('store.product',['category'=>'all'])}}" class="btn btn-outline-dark-2 btn-round btn-more"><span>Lihat Lebih Banyak Produk</span><i
                    class="icon-long-arrow-right"></i></a>
        </div><!-- End .more-container -->
    </div><!-- End .container -->

    <div class="bg-light pt-5 pb-6">
        <div class="container trending-products">
            <div class="heading heading-flex mb-3">
                <div class="heading-left">
                    <h2 class="title">Produk Hemat</h2><!-- End .title -->
                </div><!-- End .heading-left -->

                <div class="heading-right">
                    <ul class="nav nav-pills nav-border-anim justify-content-center" id="trendingTab" role="tablist">
                        @foreach ($categoryWithCheapProduct as $key => $category)
                        <li class="nav-item">
                            <a class="nav-link {{($key==0)?'active':''}}" id="trending-{{$category->kode}}-link" data-toggle="tab" href="#trending-{{$category->kode}}-tab"
                                role="tab" aria-controls="trending-{{$category->kode}}-tab" aria-selected="{{($key==0)?'true':'false'}}">{{$category->nama_kategori}}</a>
                        </li>
                        @endforeach
                    </ul>
                </div><!-- End .heading-right -->
            </div><!-- End .heading -->

            <div class="row">
                <div class="col-xl-5col d-none d-xl-block">
                    <div class="banner">
                        <a href="#">
                            <img src="{{URL::asset('/assets/img/store/banner/banner-4.jpg')}}" alt="banner">
                        </a>
                    </div><!-- End .banner -->
                </div><!-- End .col-xl-5col -->

                <div class="col-xl-4-5col">
                    <div class="tab-content tab-content-carousel just-action-icons-sm">
                        @foreach ($categoryWithCheapProduct as $key => $category)
                        <div class="tab-pane p-0 fade {{($key==0)?'show active': ''}}" id="trending-{{$category->kode}}-tab" role="tabpanel"
                            aria-labelledby="trending-{{$category->kode}}-link">
                            <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl"
                                data-owl-options='{
                                                "nav": true,
                                                "dots": false,
                                                "margin": 20,
                                                "loop": false,
                                                "responsive": {
                                                    "0": {
                                                        "items":2
                                                    },
                                                    "480": {
                                                        "items":2
                                                    },
                                                    "768": {
                                                        "items":3
                                                    },
                                                    "992": {
                                                        "items":4
                                                    }
                                                }
                                            }'>
                                @foreach ($category->newProduct as $product)
                                <div class="product product-2">
                                    <figure class="product-media">
                                        <a href="">
                                            <img src="{{(count($product->images) > 0) ? URL::asset('storage/'.$product->images[0]->url) : URL::asset('assets/img/product/product1.jpg')}}" alt="Product image"
                                                class="product-image">
                                        </a>
    {{--
                                        <div class="product-action-vertical">
                                            <a href="#" class="btn-product-icon btn-wishlist"
                                                title="Add to wishlist"></a>
                                        </div><!-- End .product-action --> --}}

                                        <div class="product-action">
                                            <a href="#" class="btn-product btn-cart" title="Add to cart" onclick="addToCart({{$product->id_produk}})"><span>masuk keranjang</span></a>
                                            <a href="{{route('store.productDetail',$product->id_produk)}}" class="btn-product btn-quickview"
                                                title="Quick view"><span>lihat produk</span></a>
                                        </div><!-- End .product-action -->
                                    </figure><!-- End .product-media -->

                                    <div class="product-body">
                                        <div class="product-cat">
                                            <a href="#">{{$category->nama_kategori}}</a>
                                        </div><!-- End .product-cat -->
                                        <h3 class="product-title"><a href="{{route('store.productDetail',$product->id_produk)}}">{{$product->nama_produk}}</a></h3><!-- End .product-title -->
                                        <div class="product-price">
                                            Rp. {{$product->harga_jual}}
                                        </div><!-- End .product-price -->
                                    </div><!-- End .product-body -->
                                </div><!-- End .product -->
                                @endforeach

                            </div><!-- End .owl-carousel -->
                        </div><!-- .End .tab-pane -->
                        @endforeach
                    </div><!-- End .tab-content -->
                </div><!-- End .col-xl-4-5col -->
            </div><!-- End .row -->
        </div><!-- End .container -->
    </div><!-- End .bg-light pt-5 pb-6 -->


    <div class="mb-5"></div><!-- End .mb-5 -->


    <div class="container">
        <hr class="mb-0">
    </div><!-- End .container -->

    @component('pages.store.component.info')

    @endcomponent
@endsection
