@extends('layouts.app')
@section('title', 'Buat Transaksi')
@section('content')
<div class="page-header">
    <h2><i class="bi bi-receipt text-primary"></i> Buat Transaksi Baru</h2>
    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary rounded-3"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<form method="POST" action="{{ route('transactions.store') }}" id="trxForm">
    @csrf
    <div class="card card-soft mb-3">
        <div class="card-body p-4">
            <h5 class="fw-semibold mb-3">Informasi Transaksi</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">No Invoice (auto)</label>
                    <input type="text" class="form-control" value="{{ $noInvPreview }}" disabled>
                    <div class="form-text">Nomor final digenerate saat disimpan.</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Customer</label>
                    <select name="kode_customer" id="kode_customer" class="form-select" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach ($customers as $c)
                            <option value="{{ $c->kode_customer }}">{{ $c->kode_customer }} - {{ $c->nama_customer }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Tanggal Invoice</label>
                    <input type="date" name="tgl_inv" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-soft mb-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-semibold mb-0">Detail Produk</h5>
                <button type="button" class="btn btn-sm btn-primary rounded-3" onclick="addRow()">
                    <i class="bi bi-plus-lg"></i> Tambah Baris
                </button>
            </div>
            <div class="table-responsive">
                <table class="table align-middle" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="min-width:200px">Produk</th>
                            <th style="width:90px">Qty</th>
                            <th style="width:130px">Harga</th>
                            <th style="width:90px">Disc 1 (%)</th>
                            <th style="width:90px">Disc 2 (%)</th>
                            <th style="width:90px">Disc 3 (%)</th>
                            <th style="width:130px">Harga Net</th>
                            <th style="width:130px">Jumlah</th>
                            <th style="width:40px"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody"></tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <h5>Total: <span class="text-primary fw-bold" id="grandTotal">Rp 0</span></h5>
            </div>
        </div>
    </div>

    <button class="btn btn-primary px-4 rounded-3"><i class="bi bi-save"></i> Simpan Transaksi</button>
</form>
@endsection

@section('scripts')
<script>
    const products = @json($productsForJs);
    let rowIndex = 0;

    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID', { maximumFractionDigits: 0 });
    }

    function addRow() {
        const i = rowIndex++;
        const options = products.map(p => `<option value="${p.kode}" data-harga="${p.harga}" data-stok="${p.stok}" data-nama="${p.nama}">${p.kode} - ${p.nama} (stok: ${p.stok})</option>`).join('');

        const row = document.createElement('tr');
        row.id = `row-${i}`;
        row.innerHTML = `
            <td>
                <select name="items[${i}][kode_produk]" class="form-select form-select-sm produk-select" onchange="onProductChange(${i})" required>
                    <option value="">-- Pilih Produk --</option>
                    ${options}
                </select>
            </td>
            <td><input type="number" min="1" name="items[${i}][qty]" class="form-control form-control-sm qty-input" onchange="calcRow(${i})" required></td>
            <td><input type="number" step="0.01" min="0" name="items[${i}][harga]" class="form-control form-control-sm harga-input" onchange="calcRow(${i})" required></td>
            <td><input type="number" step="0.01" min="0" max="100" value="0" name="items[${i}][disc1]" class="form-control form-control-sm disc-input" onchange="calcRow(${i})"></td>
            <td><input type="number" step="0.01" min="0" max="100" value="0" name="items[${i}][disc2]" class="form-control form-control-sm disc-input" onchange="calcRow(${i})"></td>
            <td><input type="number" step="0.01" min="0" max="100" value="0" name="items[${i}][disc3]" class="form-control form-control-sm disc-input" onchange="calcRow(${i})"></td>
            <td class="harga-net-cell">Rp 0</td>
            <td class="jumlah-cell fw-semibold">Rp 0</td>
            <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(${i})"><i class="bi bi-trash"></i></button></td>
        `;
        document.getElementById('itemsBody').appendChild(row);
    }

    function onProductChange(i) {
        const row = document.getElementById(`row-${i}`);
        const select = row.querySelector('.produk-select');
        const selected = select.options[select.selectedIndex];
        const hargaInput = row.querySelector('.harga-input');
        const qtyInput = row.querySelector('.qty-input');
        if (selected.value) {
            hargaInput.value = parseFloat(selected.dataset.harga).toFixed(2);
            qtyInput.max = selected.dataset.stok;
        }
        calcRow(i);
    }

    function calcRow(i) {
        const row = document.getElementById(`row-${i}`);
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
        const discInputs = row.querySelectorAll('.disc-input');
        const d1 = parseFloat(discInputs[0].value) || 0;
        const d2 = parseFloat(discInputs[1].value) || 0;
        const d3 = parseFloat(discInputs[2].value) || 0;

        let net = harga;
        net -= net * (d1 / 100);
        net -= net * (d2 / 100);
        net -= net * (d3 / 100);

        const jumlah = net * qty;

        row.querySelector('.harga-net-cell').textContent = formatRupiah(net);
        row.querySelector('.jumlah-cell').textContent = formatRupiah(jumlah);
        row.dataset.jumlah = jumlah;

        updateGrandTotal();
    }

    function removeRow(i) {
        document.getElementById(`row-${i}`).remove();
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('#itemsBody tr').forEach(row => {
            total += parseFloat(row.dataset.jumlah || 0);
        });
        document.getElementById('grandTotal').textContent = formatRupiah(total);
    }

    // mulai dengan 1 baris
    addRow();

    document.getElementById('trxForm').addEventListener('submit', function (e) {
        const rows = document.querySelectorAll('#itemsBody tr');
        if (rows.length === 0) {
            e.preventDefault();
            Swal.fire('Perhatian', 'Tambahkan minimal 1 produk.', 'warning');
        }
    });
</script>
@endsection
