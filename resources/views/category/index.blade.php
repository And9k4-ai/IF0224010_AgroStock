@extends('layouts.app')

@section('content')



<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data Kategori</h3>

    @if(!$readonly)
        <button
            class="btn btn-success"
            data-bs-toggle="modal"
            data-bs-target="#addModal">
            + Tambah
        </button>
    @endif
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <input
            type="text"
            id="search"
            class="form-control"
            placeholder="Cari kategori...">
    </div>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-success">
        <tr>
            <th width="60">No</th>
            <th>Nama Kategori</th>
            
            @if(!$readonly)
                <th width="180">Aksi</th>
            @endif
        </tr>
    </thead>

    <tbody id="tableData">
    </tbody>
</table>


@if(!$readonly)
    <!-- Modal Tambah -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="nama" class="form-control" placeholder="Nama kategori">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="saveBtn">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <input type="text" id="edit_nama" class="form-control" placeholder="Nama kategori">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="updateBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
    
    
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const table = document.getElementById('tableData');
    const search = document.getElementById('search');
    const isReadonly = @json($readonly);

    loadData();

    
    const saveBtn = document.getElementById('saveBtn');
    if (saveBtn) saveBtn.addEventListener('click', saveData);

    const updateBtn = document.getElementById('updateBtn');
    if (updateBtn) updateBtn.addEventListener('click', updateData);

    search.addEventListener('keyup', function(){
        loadData(this.value);
    });

    
    // LOAD DATA
    
    function loadData(keyword=''){
        fetch(`/categories/list?search=${keyword}`)
        .then(res=>res.json())
        .then(data=>{
            table.innerHTML='';
            let no=1;
            data.forEach(item=>{
                table.innerHTML+=`
                <tr>
                    <td>${no++}</td>
                    <td>${item.nama}</td>
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

    
    // FUNGSI CRUD (SAVE, EDIT, UPDATE, DELETE)
    
    function saveData(){
        fetch('/categories',{
            method:'POST',
            headers:{ 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN':csrfToken },
            body:JSON.stringify({ nama: document.getElementById('nama').value })
        })
        .then(res=>res.json())
        .then(result=>{
            Swal.fire({ icon:'success', title:result.message });
            const modal = bootstrap.Modal.getInstance(document.getElementById('addModal'));
            if(modal) modal.hide();
            document.getElementById('nama').value='';
            loadData();
        });
    }

    function editData(id){
        fetch(`/categories/${id}/edit`)
        .then(res => res.json())
        .then(item => {
            document.getElementById('edit_id').value = item.id;
            document.getElementById('edit_nama').value = item.nama;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    }

    function updateData(){
        let id = document.getElementById('edit_id').value;
        fetch(`/categories/${id}`, {
            method:'PUT',
            headers:{ 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN':csrfToken },
            body:JSON.stringify({ nama: document.getElementById('edit_nama').value })
        })
        .then(res => res.json())
        .then(result => {
            Swal.fire({ icon:'success', title:result.message });
            const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
            if(modal) modal.hide();
            loadData();
        });
    }

    function deleteData(id){
        Swal.fire({
            title:'Hapus kategori?', icon:'warning', showCancelButton:true, confirmButtonText:'Ya'
        }).then((result)=>{
            if(result.isConfirmed){
                fetch(`/categories/${id}`,{
                    method:'DELETE',
                    headers:{ 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN':csrfToken }
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