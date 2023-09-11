@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 style="text-decoration: underline">Result Transaksi</h3>
                <div class="row mt-4">
                    <div class="col-md-7 mb-3">
                        <label class="form-label" for="urut_column">Urutkan Berdasar</label>
                        <select id="urut_column" name="urut_column" class="select2 form-select">
                            <option value="0">Nama Barang</option>
                            <option value="1">Tanggal Transaksi</option>
                        </select>
                    </div>

                    <div class="col-3 mb-3">
                        <label for="sequence" class="form-label">Cari</label>
                        <input type="text" id="search" name="search" class="form-control" placeholder="cari" />
                    </div>


                    <div class="col-2 d-flex align-items-center typeButton">
                        <button type="button" class="btn btn-info mt-3" onclick="cariTransaksi(event)">Cari</button>
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

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h3 style="text-decoration: underline">Pembandingan Transaksi</h3>
                <div class="row mt-4">
                    <div class="col-4 mb-3">
                        <label for="startDate" class="form-label">Mulai Tanggal</label>
                        <input type="date" id="startDate" value="{{ date('Y-m-d') }}"  name="startDate" class="form-control" placeholder="cari" />
                    </div>

                    <div class="col-4 mb-3">
                        <label for="endDate" class="form-label">Akhir Tanggal</label>
                        <input type="date" id="endDate" value="{{ date('Y-m-d') }}" name="endDate" class="form-control" placeholder="cari" />
                    </div>


                    <div class="col-2 d-flex align-items-center typeButton">
                        <button type="button" class="btn btn-info mt-3" onclick="fileTransaksi(event)">Cari</button>
                    </div>
                </div>
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Barang</th>
                                        <th>Jumlah Terjual</th>
                                    </tr>
                                </thead>
                                <tbody id="tbLoadFilter"></tbody>
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
                success: function(response) {
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

        const fileTransaksi = () => {
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();
            $.ajax({
                type: "GET",
                url: "/transaksi/filterTransaksi",
                data: {
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(response) {
                    let html = ''
                    response.forEach((val, index) => {
                        html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${val.jenis_barang}</td>
                            <td>${val.jumlah_terjual}</td>
                        </tr>
                        `
                    });
                    $("#tbLoadFilter").html(html)
                }
            })
        }

        $(function() {
            cariTransaksi()
            fileTransaksi()
        })
    </script>
@endsection
