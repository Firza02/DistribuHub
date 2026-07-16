@extends('layouts.app')
@section('title', 'Dashboard Penjualan')
@section('content')
<div class="page-header">
    <h2><i class="bi bi-graph-up-arrow text-primary"></i> Dashboard Penjualan</h2>
</div>

<div class="card card-soft mb-3">
    <div class="card-body">
        <form method="GET" id="filterForm" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Dari Bulan</label>
                <input type="month" name="month_from" value="{{ $monthFrom }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Sampai Bulan</label>
                <input type="month" name="month_to" value="{{ $monthTo }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Customer</label>
                <select name="kode_customer" id="filterCustomer" class="form-select">
                    <option value="">Semua Customer</option>
                    @foreach ($customers as $c)
                        <option value="{{ $c->kode_customer }}" {{ $kodeCustomer == $c->kode_customer ? 'selected' : '' }}>
                            {{ $c->nama_customer }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Produk</label>
                <select name="kode_produk" id="filterProduk" class="form-select">
                    <option value="">Semua Produk</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->kode_produk }}" {{ $kodeProduk == $p->kode_produk ? 'selected' : '' }}>
                            {{ $p->nama_produk }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary w-100" id="filterBtn" type="submit">
                    <span id="filterIcon"><i class="bi bi-funnel"></i> Terapkan</span>
                    <span id="filterSpinner" class="spinner-border spinner-border-sm d-none"></span>
                </button>
            </div>
        </form>
        <div class="form-text mt-2">
            Isi salah satu saja (Dari Bulan atau Sampai Bulan) untuk melihat 1 bulan tertentu, atau isi keduanya untuk melihat rentang beberapa bulan/tahun.
        </div>
        @if($monthFrom || $monthTo || $kodeCustomer || $kodeProduk)
            <div class="mt-1">
                <a href="{{ route('dashboard') }}" class="small text-decoration-none">
                    <i class="bi bi-x-circle"></i> Reset filter
                </a>
            </div>
        @endif
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card card-soft h-100">
            <div class="card-body">
                <div class="text-muted small fw-semibold">TOTAL OMZET</div>
                <div class="fs-4 fw-bold text-primary">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-soft h-100">
            <div class="card-body">
                <div class="text-muted small fw-semibold">TOTAL TRANSAKSI</div>
                <div class="fs-4 fw-bold">{{ number_format($totalTransaksi, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-soft h-100">
            <div class="card-body">
                <div class="text-muted small fw-semibold">TOTAL QTY TERJUAL</div>
                <div class="fs-4 fw-bold">{{ number_format($totalQty, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-soft h-100">
            <div class="card-body">
                <div class="text-muted small fw-semibold">RATA-RATA / TRANSAKSI</div>
                <div class="fs-4 fw-bold">Rp {{ number_format($rataRata, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-7">
        <div class="card card-soft h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Tren Penjualan per Bulan</h6>
                @if($trend->isEmpty())
                    <div class="text-center text-muted py-5">Belum ada data untuk ditampilkan.</div>
                @else
                    <canvas id="trendChart" height="230"></canvas>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card card-soft h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Produk Terlaris (Top 5)</h6>
                @if($topProducts->isEmpty())
                    <div class="text-center text-muted py-5">Belum ada data untuk ditampilkan.</div>
                @else
                    <canvas id="productChart" height="230"></canvas>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card card-soft">
    <div class="card-body">
        <h6 class="fw-semibold mb-3">Customer Teratas (Top 5)</h6>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th class="text-end">Jumlah Transaksi</th>
                        <th class="text-end">Total Omzet</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topCustomers as $c)
                        <tr>
                            <td>{{ $c->kode_customer }} - {{ $c->nama_customer }}</td>
                            <td class="text-end">{{ $c->total_transaksi }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($c->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data untuk ditampilkan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    document.getElementById('filterForm').addEventListener('submit', function () {
        document.getElementById('filterIcon').classList.add('d-none');
        document.getElementById('filterSpinner').classList.remove('d-none');
        document.getElementById('filterBtn').disabled = true;
    });

    // Dropdown customer & produk dibuat bisa dicari (ketik untuk filter opsi)
    new TomSelect('#filterCustomer', {
        create: false,
        placeholder: 'Cari customer...',
        allowEmptyOption: true,
    });
    new TomSelect('#filterProduk', {
        create: false,
        placeholder: 'Cari produk...',
        allowEmptyOption: true,
    });

    @if($trend->isNotEmpty())
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: @json($trend->pluck('bulan')),
            datasets: [{
                label: 'Omzet',
                data: @json($trend->pluck('total')),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.12)',
                tension: 0.35,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#6366f1',
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { ticks: { callback: (v) => 'Rp ' + v.toLocaleString('id-ID') } }
            }
        }
    });
    @endif

    @if($topProducts->isNotEmpty())
    new Chart(document.getElementById('productChart'), {
        type: 'bar',
        data: {
            labels: @json($topProducts->pluck('nama_produk')),
            datasets: [{
                label: 'Omzet',
                data: @json($topProducts->pluck('total_revenue')),
                backgroundColor: '#22d3ee',
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { callback: (v) => 'Rp ' + v.toLocaleString('id-ID') } }
            }
        }
    });
    @endif
</script>
@endsection
