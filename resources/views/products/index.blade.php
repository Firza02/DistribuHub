@extends('layouts.app')
@section('title', 'Produk')
@section('content')
<div class="page-header">
    <h2><i class="bi bi-box text-primary"></i> Data Produk</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary rounded-3 px-4">
        <i class="bi bi-plus-lg"></i> Tambah Produk
    </a>
</div>

<div class="card card-soft mb-3">
    <div class="card-body">
        <form method="GET" class="d-flex gap-2" id="searchForm">
            <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Cari kode atau nama produk...">
            <button class="btn btn-outline-primary" id="searchBtn" type="submit">
                <span id="searchIcon"><i class="bi bi-search"></i></span>
                <span id="searchSpinner" class="spinner-border spinner-border-sm d-none"></span>
            </button>
        </form>
    </div>
</div>

<div class="card card-soft">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td><span class="badge badge-soft">{{ $product->kode_produk }}</span></td>
                            <td>{{ $product->nama_produk }}</td>
                            <td class="text-end">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                            <td class="text-end">{{ $product->stok }}</td>
                            <td class="text-center">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form id="del-{{ $product->kode_produk }}" action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete('del-{{ $product->kode_produk }}', 'Produk {{ $product->nama_produk }} akan dihapus.')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                @if($search)
                                    <i class="bi bi-search"></i> Produk "{{ $search }}" tidak ditemukan.
                                @else
                                    Belum ada data produk.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $products->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('searchForm').addEventListener('submit', function () {
        document.getElementById('searchIcon').classList.add('d-none');
        document.getElementById('searchSpinner').classList.remove('d-none');
        document.getElementById('searchBtn').disabled = true;
    });
</script>
@endsection
