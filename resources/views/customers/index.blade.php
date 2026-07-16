@extends('layouts.app')
@section('title', 'Customer')
@section('content')
<div class="page-header">
    <h2><i class="bi bi-people text-primary"></i> Data Customer</h2>
    <a href="{{ route('customers.create') }}" class="btn btn-primary rounded-3 px-4">
        <i class="bi bi-plus-lg"></i> Tambah Customer
    </a>
</div>

<div class="card card-soft mb-3">
    <div class="card-body">
        <form method="GET" class="d-flex gap-2" id="searchForm">
            <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Cari kode atau nama customer...">
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
                        <th>Kode</th>
                        <th>Nama Customer</th>
                        <th>Alamat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td><span class="badge badge-soft">{{ $customer->kode_customer }}</span></td>
                            <td>{{ $customer->nama_customer }}</td>
                            <td class="text-muted small">{{ \Illuminate\Support\Str::limit($customer->alamatSingkat(), 60) }}</td>
                            <td class="text-center">
                                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form id="del-{{ $customer->kode_customer }}" action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete('del-{{ $customer->kode_customer }}', 'Customer {{ $customer->nama_customer }} akan dihapus.')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                @if($search)
                                    <i class="bi bi-search"></i> Customer "{{ $search }}" tidak ditemukan.
                                @else
                                    Belum ada data customer.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $customers->links() }}
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
