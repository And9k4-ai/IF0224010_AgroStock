@extends('layouts.app')

@section('content')


<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data Barang</h3>

    @if(!$readonly)
        <button
            class="btn btn-success"
            data-bs-toggle="modal"
            data-bs-target="#addModal">
            + Tambah Barang
        </button>
    @endif
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <input
            type="text"
            id="search"
            class="form-control"
            placeholder="Cari barang...">
    </div>
</div>

<!-- tabel barang -->
<table class="table table-bordered table-striped">
    <thead class="table-success">
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Satuan</th>
            <th>Harga</th>
            
            @if(!$readonly)
                <th width="180">Aksi</th>
            @endif
        </tr>
    </thead>

    <tbody id="tableData">
    </tbody>
</table>


@if(!$readonly)
    <!-- modal tambah -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">
                    <select id="category_id" class="form-select mb-2">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nama }}</option>
                        @endforeach
                    </select>

                    <input type="text" id="kode" class="form-control mb-2" placeholder="Kode Barang">
                    <input type="text" id="nama_barang" class="form-control mb-2" placeholder="Nama Barang">
                    <input type="number" id="stok" class="form-control mb-2" placeholder="Stok">
                    <input type="text" id="satuan" class="form-control mb-2" placeholder="Satuan">
                    <input type="number" id="harga" class="form-control" placeholder="Harga">
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success" id="saveBtn">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal edit -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Barang</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="edit_id">

                    <select id="edit_category_id" class="form-select mb-2">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nama }}</option>
                        @endforeach
                    </select>

                    <input type="text" id="edit_kode" class="form-control mb-2">
                    <input type="text" id="edit_nama_barang" class="form-control mb-2">
                    <input type="number" id="edit_stok" class="form-control mb-2">
                    <input type="text" id="edit_satuan" class="form-control mb-2">
                    <input type="number" id="edit_harga" class="form-control">
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" id="updateBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

<!-- ajax javascript -->
@push('scripts')
<script>
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const table = document.getElementById('tableData');
    const search = document.getElementById('search');
    const isReadonly = @json($readonly);


    loadData();

    const saveBtn = document.getElementById('saveBtn');
    if (saveBtn) {
        saveBtn.addEventListener('click', saveData);
    }

    const updateBtn = document.getElementById('updateBtn');
    if (updateBtn) {
        updateBtn.addEventListener('click', updateData);
    }

    // Live search
    search.addEventListener('keyup', function(){
        loadData(this.value);
    });

    // Format mata uang rupiah
    function formatRupiah(angka){
        return new Intl.NumberFormat('id-ID',{
            style:'currency',
            currency:'IDR',
            minimumFractionDigits:0
        }).format(angka);
    }


    // LOAD DATA

    function loadData(keyword=''){
        fetch(`/products/list?search=${keyword}`)
        .then(res=>res.json())
        .then(data=>{
            table.innerHTML='';
            let no=1;
            data.forEach(item=>{
                table.innerHTML+=`
                <tr>
                    <td>${no++}</td>
                    <td>${item.kode}</td>
                    <td>${item.nama_barang}</td>
                    <td>${item.category ? item.category.nama : '-'}</td>
                    <td>${item.stok}</td>
                    <td>${item.satuan}</td>
                    <td>${formatRupiah(item.harga)}</td>
                    ${
                        !isReadonly
                        ? `
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editData(${item.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteData(${item.id})">Hapus</button>
                            </td>
                        `
                        : ''
                    }
                </tr>
                `;
            });
        });
    }


    // SIMPAN DATA

    function saveData() {
        fetch('/products', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                category_id: document.getElementById('category_id').value,
                kode: document.getElementById('kode').value,
                nama_barang: document.getElementById('nama_barang').value,
                stok: document.getElementById('stok').value,
                satuan: document.getElementById('satuan').value,
                harga: document.getElementById('harga').value
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
            Swal.fire({ icon: 'success', title: result.message });
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('addModal'));
            if (modal) modal.hide();

            document.getElementById('category_id').value = '';
            document.getElementById('kode').value = '';
            document.getElementById('nama_barang').value = '';
            document.getElementById('stok').value = '';
            document.getElementById('satuan').value = '';
            document.getElementById('harga').value = '';

            loadData();
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message || 'Terjadi kesalahan pada server.'
            });
        });
    }


    // EDIT DATA

    function editData(id){
        fetch(`/products/${id}/edit`)
        .then(res=>res.json())
        .then(item=>{
            document.getElementById('edit_id').value=item.id;
            document.getElementById('edit_category_id').value=item.category_id;
            document.getElementById('edit_kode').value=item.kode;
            document.getElementById('edit_nama_barang').value=item.nama_barang;
            document.getElementById('edit_stok').value=item.stok;
            document.getElementById('edit_satuan').value=item.satuan;
            document.getElementById('edit_harga').value=item.harga;

            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    }


    // UPDATE DATA 

    function updateData(){
        let id=document.getElementById('edit_id').value;

        fetch(`/products/${id}`,{
            method:'PUT',
            headers:{
                'Content-Type':'application/json',
                'Accept':'application/json',
                'X-CSRF-TOKEN':csrfToken
            },
            body:JSON.stringify({
                category_id:document.getElementById('edit_category_id').value,
                kode:document.getElementById('edit_kode').value,
                nama_barang:document.getElementById('edit_nama_barang').value,
                stok:document.getElementById('edit_stok').value,
                satuan:document.getElementById('edit_satuan').value,
                harga:document.getElementById('edit_harga').value
            })
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) {
                throw data;
            }
            return data;
        })
        .then(result=>{
            Swal.fire({ icon:'success', title:result.message });
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
            if (modal) modal.hide();
            
            loadData();
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message || 'Terjadi kesalahan pada server.'
            });
        });
    }


    // HAPUS DATA

    function deleteData(id){
        Swal.fire({
            title:'Hapus data?',
            text:'Data tidak dapat dikembalikan.',
            icon:'warning',
            showCancelButton:true,
            confirmButtonText:'Ya'
        }).then((result)=>{
            if(result.isConfirmed){
                fetch(`/products/${id}`,{
                    method:'DELETE',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept':'application/json',
                        'X-CSRF-TOKEN':csrfToken
                    }
                })
                .then(res=>res.json())
                .then(result=>{
                    Swal.fire({ icon:'success', title:result.message });
                    loadData();
                });
            }
        });
    }
</script>
@endpush