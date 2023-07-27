<?php $page = 'addcategory'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.pageheader')
                @slot('title')
                    Product @if (isset($category))
                        Edit
                    @else
                        Add
                    @endif Category
                @endslot
                @slot('title_1')
                    @if (isset($category))
                        Edit existing
                    @else
                        Create new
                    @endif product Category
                @endslot
            @endcomponent
            <!-- /add -->
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ isset($category) ? route('category.update', $category->id_kategori) : route('category.store') }}"
                        enctype="multipart/form-data">
                    @if (isset($category))
                        @method('PUT')
                    @else
                        @method('post')
                    @endif
                    <div class="row">
                        @csrf
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror"
                                    name="nama_kategori" value="{{ old('nama_kategori', $category->nama_kategori ?? '') }}"
                                    placeholder="category name" required>
                                @error('nama_kategori')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Category Code</label>
                                <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                    name="kode" value="{{ old('kode', $category->kode ?? '') }}"
                                    placeholder="category code" required>
                                @error('kode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="deskripsi" placeholder="description ...">{{ old('deskripsi', $category->deskripsi ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label> Category Image</label>
                                 <input type="hidden" name="oldImage" value="{{ old('image', $category->image ?? '')}}">
                                <div class="image-upload">
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                        name="image">
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
                                    @if (isset($category->image))
                                        <li>
                                            <div class="productviews">
                                                <div class="productviewsimg">
                                                    <img src="{{ URL::asset('storage/uploads/image/product/category/'.$category->image)}}" alt="img">
                                                </div>
                                                <div class="productviewscontent">
                                                    <div class="productviewsname">
                                                        <h2>{{$category->image}}</h2>
                                                    </div>
                                                    <a onclick="removeList()">x</a>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-submit me-2">Submit<button>
                            <a href="{{ route('category.index') }}" class="btn btn-cancel">Cancel</a>
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

    $("#image").change(function () {
        const file = this.files[0];
        removeList();
        if (file) {
            let reader = new FileReader();
            let imgName = file.name;
            let imgSize = file.size/1024
            reader.onload = function (event) {
                $(".imgpreview")
                  .attr("src", event.target.result);
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
    });
    function removeList() {
        $('.list-img li').remove();
    }
</script>
@endsection
