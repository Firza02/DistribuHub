@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('content')
<div class="page-header">
    <h2><i class="bi bi-plus-circle text-primary"></i> Tambah Produk</h2>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-3"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card card-soft">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Produk</label>
                <input type="text" name="kode_produk" value="{{ old('kode_produk') }}" class="form-control" placeholder="cth: PRD001" required>
                <div class="form-text">Hanya huruf & angka, tanpa spasi/karakter spesial.</div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Produk</label>
                <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" class="form-control" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Harga</label>
                    <input type="number" step="0.01" min="0" name="harga" value="{{ old('harga') }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Stok</label>
                    <input type="number" min="0" name="stok" value="{{ old('stok') }}" class="form-control" required>
                </div>
            </div>
            <button class="btn btn-primary px-4 rounded-3"><i class="bi bi-save"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection
