@extends('layouts.app')

@section('content')

<!-- ==========================================
     HTML VIEW BARANG KELUAR
     ========================================== -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Barang Keluar</h3>
    <button
        class="btn btn-danger"
        data-bs-toggle="modal"
        data-bs-target="#addModal">
        + Tambah
    </button>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-danger">
        <tr>
            <th width="60">No</th>
            <th>Tanggal</th>
            <th>Barang</th>
            <th>Jumlah</th>
            <th width="120">Aksi</th>
        </tr>
    </thead>
    <tbody id="tableData">
    </tbody>
</table>

<!-- ==========================================
     MODAL TAMBAH BARANG KELUAR
     ========================================== -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Keluar</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                <label class="form-label">Barang</label>
                <select id="product_id" class="form-select mb-3">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->nama_barang }}
                        </option>
                    @endforeach
                </select>

                <label class="form-label">Jumlah</label>
                <input type="number" id="jumlah" class="form-control mb-3">

                <label class="form-label">Tanggal</label>
                <input type="date" id="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Batal
                </button>
                <button
                    type="button"
                    class="btn btn-danger"
                    id="saveBtn">
                    Simpan
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ==========================================
    // INITIALIZATION & GLOBAL VARIABLES
    // ==========================================
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');

    const table = document.getElementById('tableData');

    // Load data pertama kali saat halaman dibuka
    loadData();

    // ==========================================
    // EVENT LISTENERS
    // ==========================================
    document
        .getElementById('saveBtn')
        .addEventListener('click', saveData);

    // ==========================================
    // LOAD DATA BARANG KELUAR
    // ==========================================
    function loadData() {
        fetch('/stock-outs/list')
        .then(res => res.json())
        .then(data => {
            table.innerHTML = '';
            let no = 1;
            data.forEach(item => {
                table.innerHTML += `
                <tr>
                    <td>${no++}</td>
                    <td>${item.tanggal}</td>
                    <td>${item.product.nama_barang}</td>
                    <td>${item.jumlah}</td>
                    <td>-</td>
                </tr>
                `;
            });
        });
    }

    // ==========================================
    // SIMPAN DATA BARANG KELUAR
    // ==========================================
    function saveData() {
        fetch('/stock-outs', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                product_id: document.getElementById('product_id').value,
                jumlah: document.getElementById('jumlah').value,
                tanggal: document.getElementById('tanggal').value
            })
        })
        .then(async res => {
            const data = await res.json();

            if (!res.ok) {
                throw data;
            }

            return data;
        })
        .then(result => {
            Swal.fire({
                icon: 'success',
                title: result.message
            });

            bootstrap.Modal
                .getInstance(document.getElementById('addModal'))
                .hide();

            document.getElementById('jumlah').value = '';
            loadData();
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message || 'Terjadi kesalahan sistem.'
            });
        });
    }
</script>
@endpush