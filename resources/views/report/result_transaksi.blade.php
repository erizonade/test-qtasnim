@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 style="text-decoration: underline">Result Transaksi</h3>
                <div class="row mt-4">
                    <div class="col-md-7 mb-3">
                        <label class="form-label" for="urut_column">Urutkan Berdasar</label>
                        <select id="urut_column" onchange="urutColumn(event, this.value)" name="urut_column" class="select2 form-select">
                            <option value="0">Nama Barang</option>
                            <option value="1">Tanggal Transaksi</option>
                        </select>
                    </div>

                    <div class="col-3 mb-3">
                        <label for="sequence" class="form-label">Cari</label>
                        <input type="text" id="search" onkeyup="searchTransaksi(event)" name="search" class="form-control" placeholder="cari" />
                    </div>

                </div>
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Stok</th>
                                        <th>Jumlah Terjual</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Jenis Barang</th>
                                    </tr>
                                </thead>
                                <tbody id="tbLoadHasil"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const urutColumn = (event, value) => {
            cariTransaksi(event)
        }

        const searchTransaksi = (event) => {
            cariTransaksi(event)
        }

        const cariTransaksi = (event) => {

            let search = $('#search').val();
            let column = $('#urut_column').val();

            $.ajax({
                type: "GET",
                url: "/transaksi/searchTransaksi",
                data: {
                    search: search,
                    column: column
                },
                beforeSend : function () {
                    myswalloading()
                },
                success: function(response) {
                    Swal.close()
                    let html = ''
                    response.forEach((val, index) => {
                        html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${val.nama_barang}</td>
                            <td>${val.stok}</td>
                            <td>${val.jumlah_terjual}</td>
                            <td>${val.tanggal_transaksi}</td>
                            <td>${val.jenis_barang}</td>
                        </tr>
                        `
                    });
                    $("#tbLoadHasil").html(html)
                }
            })
        }

        $(function() {
            cariTransaksi()
        })
    </script>
@endsection
