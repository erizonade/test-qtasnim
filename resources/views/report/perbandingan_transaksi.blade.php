@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 style="text-decoration: underline">Pembandingan Transaksi</h3>
                <div class="row mt-4">
                    <div class="col-4 mb-3">
                        <label for="startDate" class="form-label">Mulai Tanggal</label>
                        <input type="date" id="startDate" onchange="startDate(event)" value="{{ date('Y-m-d') }}" name="startDate" class="form-control"
                            placeholder="cari" />
                    </div>

                    <div class="col-4 mb-3">
                        <label for="endDate" class="form-label">Akhir Tanggal</label>
                        <input type="date" id="endDate" onchange="endDate(event)" value="{{ date('Y-m-d') }}" name="endDate" class="form-control"
                            placeholder="cari" />
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
        const startDate = (event) => {
            fileTransaksi()
        }
        const endDate = (event) => {
            fileTransaksi()
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
            fileTransaksi()
        })
    </script>
@endsection
