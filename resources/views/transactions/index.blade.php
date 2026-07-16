@extends('layouts.app')
@section('title', 'Transaksi')
@section('content')
<div class="page-header">
    <h2><i class="bi bi-receipt text-primary"></i> Data Transaksi</h2>
    <a href="{{ route('transactions.create') }}" class="btn btn-primary rounded-3 px-4">
        <i class="bi bi-plus-lg"></i> Buat Transaksi
    </a>
</div>

<div class="card card-soft mb-3">
    <div class="card-body">
        <form method="GET" class="d-flex gap-2" id="searchForm">
            <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Cari no invoice atau nama customer...">
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
                        <th>No Invoice</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $trx)
                        <tr>
                            <td><span class="badge badge-soft">{{ $trx->no_inv }}</span></td>
                            <td>{{ $trx->nama_customer }}</td>
                            <td>{{ \Carbon\Carbon::parse($trx->tgl_inv)->format('d M Y') }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ url('/transactions/'.$trx->no_inv) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                @if($search)
                                    <i class="bi bi-search"></i> Transaksi "{{ $search }}" tidak ditemukan.
                                @else
                                    Belum ada data transaksi.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $transactions->links() }}
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
