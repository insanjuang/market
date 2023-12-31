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
                        <img src="assets/images/demos/demo-4/banners/banner-4.jpg" alt="banner">
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
                                        <img src="{{($product->image == "") ? URL::asset('assets/img/product/product1.jpg') : URL::asset('/storage'.$product->image)}}" alt="Product image"
                                            class="product-image">
                                    </a>
{{--
                                    <div class="product-action-vertical">
                                        <a href="#" class="btn-product-icon btn-wishlist"
                                            title="Add to wishlist"></a>
                                    </div><!-- End .product-action --> --}}

                                    <div class="product-action">
                                        <a href="#" class="btn-product btn-cart" title="Add to cart"><span>add to
                                                cart</span></a>
                                        <a href="popup/quickView.html" class="btn-product btn-quickview"
                                            title="Quick view"><span>quick view</span></a>
                                    </div><!-- End .product-action -->
                                </figure><!-- End .product-media -->

                                <div class="product-body">
                                    <div class="product-cat">
                                        <a href="#">{{$category->nama_kategori}}</a>
                                    </div><!-- End .product-cat -->
                                    <h3 class="product-title"><a href="product.html">{{$product->nama_produk}}</a></h3><!-- End .product-title -->
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
