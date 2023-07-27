<?php $page = 'addsupplier'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.pageheader')
                @slot('title') Supplier Management @endslot
			    @slot('title_1') Add/Update Supplier @endslot
            @endcomponent
            <!-- /add -->
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ isset($supplier) ? route('supplier.update', $supplier->id_supplier) : route('supplier.store') }}">
                    @if (isset($supplier))
                        @method('PUT')
                    @else
                        @method('post')
                    @endif
                    <div class="row">
                        @csrf
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Supplier Name</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    name="nama" value="{{ old('nama', $supplier->nama ?? '') }}"
                                    placeholder="Supplier Name" required>
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                         <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                    name="telepon" value="{{ old('telepon', $supplier->telepon ?? '') }}"
                                    placeholder="+628XX / 08XXX" required>
                                @error('telepon')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                         <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email', $supplier->email ?? '') }}"
                                    placeholder="supplier@example.com" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Country</label>
                                <select class="form-control select-negara" id="negara" name="negara" placeholder="Choose Country" required>
                                    {{-- @foreach($country as $cc)
                                        <option value="{{$cc->kode}}" {{ old('negara', $supplier->negara) == $cc->kode ? 'selected' : ''}}>
                                            {{$cc->nama}}
                                        </option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Province</label>
                                <select class="form-control select-provinsi" id="provinsi" name="provinsi" placeholder="Choose Province" required>
                                    {{-- @foreach($country as $cc)
                                        <option value="{{$cc->kode}}" {{ old('negara', $supplier->negara) == $cc->kode ? 'selected' : ''}}>
                                            {{$cc->nama}}
                                        </option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>City</label>
                                <select class="form-control select-kotakab" id="kotakab" name="kotakab" placeholder="Choose City" required>
                                    {{-- @foreach($country as $cc)
                                        <option value="{{$cc->kode}}" {{ old('negara', $supplier->negara) == $cc->kode ? 'selected' : ''}}>
                                            {{$cc->nama}}
                                        </option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control @error('alamat') is-invalid @enderror"
                                    name="alamat" value="{{ old('alamat', $supplier->alamat ?? '') }}"
                                    placeholder="Address" required>
                                @error('alamat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="deskripsi" placeholder="description ...">{{ old('deskripsi', $supplier->deskripsi ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-submit me-2">Submit<button>
                            <a href="{{ route('supplier.index') }}" class="btn btn-cancel">Cancel</a>
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
    $('.select-negara').select2({
        placeholder: 'Select Country',
        ajax: {
            url: '{{ route('wilayah.json', 'state') }}',
            dataType: 'json',
            data: { 'parent': '' },
            delay: 100,
            processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.nama,
                        id: item.kode
                    }
                })
            };
            },
            cache: true
        }
    });

    $('.select-provinsi').select2({
        placeholder: 'Select Province',
    });
    $('.select-negara').on('select2:select', function (e) {
        console.log($('#negara').val());
        $('.select-provinsi').select2({
            placeholder: 'Select Province',
            ajax: {
                url: '{{ route('wilayah.json', 'province') }}',
                dataType: 'json',
                data: { 'parent': $('#negara').val() },
                delay: 100,
                processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.nama,
                            id: item.kode
                        }
                    })
                };
                },
                cache: true
            }
        });
    });
    $('.select-kotakab').select2({
        placeholder: 'Select City'
    });
    $('.select-provinsi').on('select2:select', function (e) {
        $('.select-kotakab').select2({
            placeholder: 'Select City',
            ajax: {
                url: '{{ route('wilayah.json', 'city') }}',
                dataType: 'json',
                data: { 'parent': $('#provinsi').val() },
                delay: 100,
                processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.nama,
                            id: item.kode
                        }
                    })
                };
                },
                cache: true
            }
        });
    });
</script>
@endsection
