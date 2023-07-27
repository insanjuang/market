<?php $page="addproduct";?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        @component('components.pageheader')
			@slot('title') Product Add @endslot
			@slot('title_1') Create new product @endslot
		@endcomponent
        <!-- /add -->
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ isset($produk) ? route('product.update', $produk->id_produk) : route('product.store') }}"
                        enctype="multipart/form-data">
                    @if (isset($produk))
                        @method('PUT')
                    @else
                        @method('post')
                    @endif
                    @csrf
                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Supplier</label>
                            <select class="form-control select-supplier" name="id_supplier" placeholder="Select Supplier" required>
                                <option></option>
                                @foreach($supplier as $spl)
                                    <option value="{{$spl->id_supplier}}" {{ old('id_supplier', $produk->id_supplier ?? '') == $spl->id_supplier ? 'selected' : ''}}>
                                        {{$spl->nama}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control select-kategori" id="kategori" name="id_kategori" placeholder="Select Category" required>
                                <option></option>
                                @foreach($kategori as $kat)
                                    <option value="{{$kat->kode}}" {{ old('id_kategori', $produk->id_kategori ?? '') == $kat->id_kategori ? 'selected' : ''}}>
                                        {{$kat->kode." - ".$kat->nama_kategori}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Sub Category</label>
                            <select class="form-control select-subkategori" id="subkategori" name="id_subkategori" required>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" name="nama_produk" placeholder="Product Name" value="{{ old('nama_produk', $produk->nama_produk ?? '') }}" required>
                            @error('nama_produk')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Product Code / SKU</label>
                            <input type="text" class="form-control @error('kode_produk') is-invalid @enderror" name="kode_produk" placeholder="Product Code" value="{{ old('kode_produk', $produk->kode_produk ?? '') }}" required>
                            @error('kode_produk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description" placeholder="Description ...">{{ old('description', $produk->description ?? '') }}</textarea>
                        </div>
                    </div>

                    <hr>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Purchase Price</label>
                            <input type="number" class="form-control @error('harga_beli') is-invalid @enderror" name="harga_beli" placeholder="0" value="{{ old('harga_beli', $produk->harga_beli ?? '') }}" min="1" required>
                            @error('harga_beli')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Agent Price</label>
                            <input type="number" class="form-control @error('harga_agen') is-invalid @enderror" name="harga_agen" placeholder="0" value="{{ old('harga_agen', $produk->harga_agen ?? '') }}" min="1" required>
                            @error('harga_agen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Reseller Price</label>
                            <input type="number" class="form-control @error('harga_reseller') is-invalid @enderror" name="harga_reseller" placeholder="0" value="{{ old('harga_reseller', $produk->harga_reseller ?? '') }}" min="1" required>
                            @error('harga_reseller')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Selling Price</label>
                            <input type="number" class="form-control @error('harga_jual') is-invalid @enderror" name="harga_jual" placeholder="0" value="{{ old('harga_jual', $produk->harga_jual ?? '') }}" min="1" required>
                            @error('harga_jual')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Discount % (Percent)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" @error('diskon_persen') is-invalid @enderror" name="diskon_persen" placeholder="0" value="{{ old('diskon_persen', $produk->diskon_persen ?? '') }}" min="0" max="100">
                                @error('diskon_persen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Discount Amount</label>
                            <input type="number" class="form-control" @error('diskon_rp') is-invalid @enderror" name="diskon_rp" placeholder="0" value="{{ old('diskon_rp', $produk->diskon_rp ?? '') }}" min="0">
                            @error('diskon_rp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Minimum Order</label>
                            <input type="number" class="form-control @error('min_order') is-invalid @enderror" name="min_order" placeholder="1" value="{{ old('min_order', $produk->min_order ?? '1') }}" min="1" required>
                            @error('min_order')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Weight</label>
                            <div class="input-group">
                                <input type="number" class="form-control" @error('berat') is-invalid @enderror" name="berat" placeholder="0" value="{{ old('berat', $produk->berat ?? '') }}" min="0" required>
                                @error('berat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <span class="input-group-text">gram</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>PreOrder</label>
                            <div class="status-toggled-flex justify-content-between align-items-center">
                                <input type="checkbox" id="preorder" class="check" name="preorder" {{ old('preorder', $produk->preorder ?? '') == 1 ? 'checked' : ''}}>
                                <label for="preorder" class="checktoggle"></label>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Dimension</label>
                            <select class="select">
                                <option>Closed</option>
                                <option>Open</option>
                            </select>
                        </div>
                    </div> --}}
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="status-toggle d-flex justify-content-between align-items-center">
                                <input type="checkbox" id="status" class="check" name="is_active" {{ old('is_active', $produk->is_active ?? '') == 1 ? 'checked' : ''}}>
                                <label for="status" class="checktoggle"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Product Image</label>
                            {{-- <input type="hidden" name="oldImage" value="{{ old('image', $produk->image ?? '')}}"> --}}
                            <div class="image-upload">
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                    name="image[]" multiple required>
                                <div class="image-uploads">
                                    <img src="{{ URL::asset('/assets/img/icons/upload.svg') }}" alt="img">
                                    <h4>Drag and drop a file to upload</h4>
                                </div>
                            </div>
                            @error('image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                            <div class="product-list">
                                <ul class="row list-img">
                                    @if (count($produk->images) > 0)
                                    @foreach ( $produk->images as $image )
                                        <li>
                                            <div class="productviews">
                                                <div class="productviewsimg">
                                                    <img src="{{ URL::asset('storage/'.$image->url)}}" alt="img">
                                                </div>
                                                <div class="productviewscontent">
                                                    <div class="productviewsname">
                                                        <h2>{{$image->url}}</h2>
                                                    </div>
                                                    <a onclick="removeList()">x</a>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit<button>
                        <a href="{{route('product.index')}}" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <!-- /add -->
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">

    $('.select-supplier').select2({
        placeholder: 'Select Supplier',
    });
    $('.select-kategori').select2({
        placeholder: 'Select Category',
    });
    $('.select-subkategori').select2({
        placeholder: 'Select Sub Category',
    });
    $('.select-kategori').on('select2:select', function (e) {
        console.log($('#kategori').val());
        $('.select-subkategori').select2({
            placeholder: 'Select Sub Category',
            ajax: {
                url: '{{ route('subcategory.json') }}',
                dataType: 'json',
                data: { 'parent_code': $('#kategori').val() },
                delay: 100,
                processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.kode+ " - " + item.nama_kategori,
                            id: item.id_kategori
                        }
                    })
                };
                },
                cache: true
            }
        });
    });

    $("#image").change(function () {
        const file = this.files[0];
        removeList();
        console.log(this.files);
        console.log(file);
        if (this.files.length > 0)
        {
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];

                let reader = new FileReader();
                let imgName = file.name;
                let imgSize = file.size/1024
                reader.onload = function (event) {
                    // $(".imgpreview").attr("src", event.target.result);
                    let imgElement = '<li>' +
                        '<div class="productviews">' +
                            '<div class="productviewsimg">' +
                                '<img class="imgpreview" src="'+event.target.result+'" alt="img">' +
                            '</div>' +
                            '<div class="productviewscontent">' +
                                '<div class="productviewsname">' +
                                    ' <h2>'+imgName+'</h2>' +
                                    ' <h3>'+imgSize.toFixed(1)+' kB</h3>' +
                                '</div>' +
                                '<a onclick="removeList()">x</a>' +
                            '</div>' +
                        ' </div>' +
                        '</li>';
                    $('.list-img').append(imgElement);
                };
                reader.readAsDataURL(file);
            }
        }
    });
    function removeList() {
        $('.list-img li').remove();
    }

</script>
@endsection
