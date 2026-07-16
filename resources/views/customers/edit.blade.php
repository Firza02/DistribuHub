@extends('layouts.app')
@section('title', 'Edit Customer')
@section('content')
<div class="page-header">
    <h2><i class="bi bi-pencil-square text-primary"></i> Edit Customer</h2>
    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary rounded-3"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card card-soft">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('customers.update', $customer) }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Kode Customer</label>
                    <input type="text" value="{{ $customer->kode_customer }}" class="form-control" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nama Customer</label>
                    <input type="text" name="nama_customer" value="{{ old('nama_customer', $customer->nama_customer) }}" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                <textarea name="alamat_lengkap" class="form-control" rows="2" required>{{ old('alamat_lengkap', $customer->alamat_lengkap) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Provinsi <span class="text-danger">*</span></label>
                    <input type="text" name="provinsi" value="{{ old('provinsi', $customer->provinsi) }}" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Kota <span class="text-danger">*</span></label>
                    <input type="text" name="kota" value="{{ old('kota', $customer->kota) }}" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Kecamatan <span class="text-danger">*</span></label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $customer->kecamatan) }}" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Kelurahan <span class="text-danger">*</span></label>
                    <input type="text" name="kelurahan" value="{{ old('kelurahan', $customer->kelurahan) }}" class="form-control" required>
                </div>
            </div>
            <div class="mb-3 col-md-3">
                <label class="form-label fw-semibold">Kode Pos <span class="text-danger">*</span></label>
                <input type="text" name="kode_pos" value="{{ old('kode_pos', $customer->kode_pos) }}" class="form-control" required>
            </div>
            <button class="btn btn-primary px-4 rounded-3"><i class="bi bi-save"></i> Update</button>
        </form>
    </div>
</div>
@endsection
