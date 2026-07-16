@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('content')
<div class="page-header">
    <h2><i class="bi bi-receipt text-primary"></i> Detail Transaksi</h2>
    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary rounded-3"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card card-soft mb-3">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-3"><strong>No Invoice</strong><div>{{ $transaction->no_inv }}</div></div>
            <div class="col-md-3"><strong>Customer</strong><div>{{ $transaction->kode_customer }} - {{ $transaction->nama_customer }}</div></div>
            <div class="col-md-3"><strong>Tanggal</strong><div>{{ \Carbon\Carbon::parse($transaction->tgl_inv)->format('d M Y') }}</div></div>
            <div class="col-md-3"><strong>Total</strong><div class="text-primary fw-bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</div></div>
        </div>
        <hr>
        <strong>Alamat</strong>
        <div class="text-muted">{{ $transaction->alamat }}</div>
    </div>
</div>

<div class="card card-soft">
    <div class="card-body p-4">
        <h5 class="fw-semibold mb-3">Detail Produk</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Disc 1</th>
                        <th class="text-end">Disc 2</th>
                        <th class="text-end">Disc 3</th>
                        <th class="text-end">Harga Net</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->details as $d)
                        <tr>
                            <td>{{ $d->kode_produk }} - {{ $d->nama_produk }}</td>
                            <td class="text-end">{{ $d->qty }}</td>
                            <td class="text-end">Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                            <td class="text-end">{{ $d->disc1 }}%</td>
                            <td class="text-end">{{ $d->disc2 }}%</td>
                            <td class="text-end">{{ $d->disc3 }}%</td>
                            <td class="text-end">Rp {{ number_format($d->harga_net, 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($d->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="text-end fw-bold">TOTAL</td>
                        <td class="text-end fw-bold text-primary">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
