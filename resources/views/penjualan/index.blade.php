@extends('layouts.master')

@section('title')
    Daftar Penjualan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Penjualan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body table-responsive">

@csrf  

@method('PUT')
            <div class="row nt-12">
            <div class="col-lg-12">
            <form method="post" class="form-inline">
            @method('PUT')
            @csrf
                <input type="date" name="tgl_mulai" class="form-control">
                <input type="date" name="tgl_selesai" class="form-control ml-3">
                <button type="submit" name="filter_tgl" class="btn btn-info ml-3">Filter</button>
            </form>  
            </div>  
            </div> 
            <?php
            if (isset($_POST['filter_tgl'])) {
                if ($mulai!=null || $selesai!=null) {
                $ambilsemuadatastock = mysqli_query($conn,"SELECT * FROM penjualan"); 
                }
                $mulai = $_POST['tgl_mulai'];
                $selesai = $_POST['tgl_selesai'];
                $ambilsemuadatastock = mysqli_query($conn,"SELECT * FROM penjualan WHERE created_at between '$mulai' AND DATE_ADD('$selesai',INTERVAL 1 DAY) order by id_user DESC");
            }
            ?>
            <br>
                <table class="table table-stiped table-bordered table-penjualan">
                <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Kode Member</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Diskon</th>
                        <th>Total Bayar</th>
                        <th>Kasir</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('penjualan.detail')
@includeIf('produk.detail')
@endsection


@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-penjualan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('penjualan.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'kode_member'},
                {data: 'total_item'},
                {data: 'total_harga'},
                {data: 'diskon'},
                {data: 'bayar'},
                {data: 'kasir'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_jual'},
                {data: 'jumlah'},
                {data: 'subtotal'},
            ]
        })
    });

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
</script>
@endpush