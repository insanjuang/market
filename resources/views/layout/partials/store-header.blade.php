<header class="header header-intro-clearance header-4">
    <div class="header-middle">
        <div class="container">
            <div class="header-left">
                {{-- <button class="mobile-menu-toggler">
                            <span class="sr-only">Toggle mobile menu</span>
                            <i class="icon-bars"></i>
                        </button> --}}

                <a href="{{ route('store') }}" class="logo">
                    <img src="{{ URL::asset('/assets/img/logo.png') }}" alt="Adin Logo" width="105" height="25">
                </a>
            </div><!-- End .header-left -->

            <div class="header-center">
                <div class="header-search header-search-extended header-search-visible d-none d-lg-block">
                    <a href="#" class="search-toggle" role="button"><i class="icon-search"></i></a>
                    <form action="#" method="get">
                        <div class="header-search-wrapper search-wrapper-wide">
                            <label for="q" class="sr-only">Search</label>
                            <button class="btn btn-primary" type="submit"><i class="icon-search"></i></button>
                            <input type="search" class="form-control" name="q" id="q"
                                placeholder="Search product ..." required>
                        </div><!-- End .header-search-wrapper -->
                    </form>
                </div><!-- End .header-search -->
            </div>

            <div class="header-right">
                {{-- <div class="wishlist">
                            <a href="wishlist.html" title="Wishlist">
                                <div class="icon">
                                    <i class="icon-heart-o"></i>
                                    <span class="wishlist-count badge">3</span>
                                </div>
                                <p>Wishlist</p>
                            </a>
                        </div><!-- End .compare-dropdown --> --}}

                <div class="dropdown cart-dropdown">
                    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" data-display="static">
                        <div class="icon">
                            <i class="icon-shopping-cart"></i>
                            <span class="cart-count" id="cart-count">{{Cart::getTotalQuantity()}}</span>
                        </div>
                        <p>Cart</p>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-cart-products">
                            @foreach (Cart::getContent() as $item)
                            <div class="product" id="product-{{$item->id}}">
                                <div class="product-cart-details">
                                    <h4 class="product-title">
                                        <a href="product.html">{{$item->name}}</a>
                                    </h4>

                                    <span class="cart-product-info">
                                        <span class="cart-product-qty">{{$item->quantity}}</span>
                                        x Rp. {{$item->price}}
                                    </span>
                                </div><!-- End .product-cart-details -->

                                <figure class="product-image-container">
                                    <a href="#" class="product-image">
                                        <img src="{{$item->attributes->image}}" alt="product">
                                    </a>
                                </figure>
                                <a href="#" class="btn-remove" title="Remove Product" onclick="removeItem({{$item->id}},'{{route('store.removeCart',$item->id)}}')"><i
                                        class="icon-close"></i></a>
                            </div><!-- End .product -->
                            @endforeach

                        </div><!-- End .cart-product -->

                        <div class="dropdown-cart-total">
                            <span>Total</span>

                            <span class="cart-total-price" id="cart-total-price">Rp. {{Cart::getTotal()}}</span>
                        </div><!-- End .dropdown-cart-total -->

                        <div class="dropdown-cart-action">
                            <a href="{{route('store.cartList')}}" class="btn btn-primary">Keranjang</a>
                            <a href="{{ route('store.checkout') }}" class="btn btn-outline-primary-2"><span>Proses</span><i
                                    class="icon-long-arrow-right"></i></a>
                        </div><!-- End .dropdown-cart-total -->
                    </div><!-- End .dropdown-menu -->
                </div><!-- End .cart-dropdown -->
            </div><!-- End .header-right -->
        </div><!-- End .container -->
    </div><!-- End .header-middle -->
</header><!-- End .header -->
