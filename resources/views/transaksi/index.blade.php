@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3>Transaksi</h3>
                        <button class="btn btn-sm btn-info" onclick="addModal()">Tambah</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover" id="tbTransaksi">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Invoice</th>
                                <th>Tanggal Transaksi</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @component('component.modal')
        @slot('idModal')
            transaksi
        @endslot
        @slot('sizeModal')
            modal-xl
        @endslot
        @slot('title')
            Transaksi
        @endslot
        @slot('idform')
            formTransaksi
        @endslot
        @slot('addForm')
            <form id="formTransaksi">
                <input type="hidden" id="idTransaksi" name="idTransaksi">
                <div class="row">
                    <div class="col-md-6">
                        <label for="number_invoice" class="form-label">Invoice</label>
                        <input type="text" disabled class="form-control" value="{{ invoice() }}" id="number_invoice"
                            name="number_invoice">
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi</label>
                        <input type="text" value="{{ now()->toDateString() }}" disabled class="form-control"
                            id="tanggal_transaksi" name="tanggal_transaksi">
                    </div>
                </div>
                <div class="form-group mt-4">
                    <h6 class="fw-semibold">Transaksi Detail</h6>
                    <hr class="mt-0" />

                    <div class="row " id="rowTransaksiDetail">
                        <div class="col-md-7 mb-3">
                            <label class="form-label" for="barang_id">Barang</label>
                            <select id="barang_id" name="barang_id" class="select2 form-select">
                                <option value="">Select Barang</option>
                            </select>
                        </div>

                        <div class="col-3 mb-3">
                            <label for="sequence" class="form-label">Quantity</label>
                            <input type="text" oninput="validateInput(this)" min="0" id="quantity" name="quantity"
                                class="form-control" />
                        </div>


                        <div class="col-2 d-flex align-items-center typeButton">
                            <button type="button" class="btn btn-info mt-3" onclick="addTransaksiDetail(event)">+</button>
                        </div>
                    </div>
                    <div class="col">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="10%">Jenis Barang</th>
                                        <th>Barang</th>
                                        <th width="10%" style="text-align: center">Stok</th>
                                        <th width="10%" style="text-align: center">Quantity</th>
                                        <th width="10%" style="text-align: center">Harga</th>
                                        <th width="10%" style="text-align: center">Total</th>
                                        <th width="10%" class="actionNone" style="text-align: center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyTransaksiDetail" class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        @endslot
    @endcomponent
@endsection
@section('scripts')
    <script>
        const url = '/transaksi'

        const addModal = () => {
            $("#transaksi").modal("show")
            barangLoop = []

            $("#rowTransaksiDetail").removeClass("d-none")
            onLoadBarang()

            barang()
        }

        const detail = (id) => {
            $("#transaksi").modal("show")
            $.ajax({
                type: "GET",
                url: `${url}/${id}`,
                success: function(response) {
                    barangLoop = []
                    $("#rowTransaksiDetail").addClass("d-none")

                    $("#number_invoice").val(response.nomor_transaksi)
                    $("#tanggal_transaksi").val(response.tanggal_transaksi)

                    response.transaksi_detail.forEach(val => {
                        barangLoop.push({
                            id: val.id,
                            quantity: val.quantity,
                            harga: val.harga,
                            total: parseInt(val.quantity + val.harga),
                            jenis_barang: val.barang.jenis_barang.nama,
                            stok: val.barang.stok,
                            status : 1,
                            nama_barang: val.barang.nama_barang
                        })
                    })
                    onLoadBarang()
                }
            });
        }


        const saveForm = () => {
            removeXhr()
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    transaksiBarang: barangLoop
                },
                error: function(xhr) {
                    handleErrorXhr(xhr)
                },
                success: function(response) {
                    if (response.status == 200) {
                        $("#transaksi").modal("hide")
                        loadTransaksi()
                        responSwalAlert('end', 'success', response.message)
                    } else {
                        responSwalAlert('end', 'error', response.message)
                    }
                }
            });
        }

        const loadTransaksi = () => {
            $("#tbTransaksi").DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                bDestroy: true,
                ajax: `${url}`,
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'text-center',
                    },
                    {
                        data: 'nomor_transaksi',
                        className: 'text-left',
                    },
                    {
                        data: 'tanggal_transaksi',
                        className: 'text-left',
                    },
                    {
                        data: 'total',
                        className: 'text-left',
                    },
                    {
                        data: 'action',
                        className: 'text-center',
                    }
                ]
            })
        }

        const barang = () => {
            $.ajax({
                type: "GET",
                url: "/barang/getAllBarang",
                success: function(response) {
                    let option = ''
                    option += `<option value="">Pilih Barang</option>`
                    response.forEach(val => {
                        option +=
                            `<option value="${val.id}" data-barang="${val.harga}|${val.jenis_barang.nama}|${val.stok}">${val.nama_barang}</option>`
                    })
                    $("#barang_id").html(option)
                }
            });
        }


        let barangLoop = []
        const addTransaksiDetail = () => {
            if (checkValue($("#quantity").val()) || checkValue($("#barang_id").val())) return sweetAlert('Warning!',
                'Quantity and Barang is required!', 'warning')

            let barangId = $("#barang_id").val()
            let quantity = parseInt($("#quantity").val())

            const [harga, jenis_barang, stok] = $("#barang_id").find("option:selected").data("barang").split("|")

            const check = barangLoop.find(v => v.id == barangId)
            if (check) {
                check.quantity = quantity
            } else {
                barangLoop.push({
                    id: barangId,
                    quantity: quantity,
                    harga: harga,
                    total: parseInt(quantity + harga),
                    jenis_barang: jenis_barang,
                    stok: stok,
                    status : 0,
                    nama_barang: $("#barang_id option:selected").text()
                })

            }
            onLoadBarang()
        }

        const onLoadBarang = () => {
            let tbody = ''
            barangLoop.forEach((v, i) => {
                let quantity = ''
                if (v.status == 0) {
                    quantity = `<input type="text" oninput="validateInput(this)" min="0" id="quantityEdit_${v.id}" name="quantityEdit"
                                            class="form-control" onkeyup="updateQuantity(this, ${v.id})" value="${v.quantity}" /></td>`
                } else {
                    quantity = `${v.quantity}`
                }
                tbody += `<tr class="row${v.id}">
                                <td>${v.jenis_barang}</td>
                                <td>${v.nama_barang}</td>
                                <td>${v.stok}</td>
                                <td class="text-center">
                                    ${quantity}
                                <td>${v.harga}</td>
                                <td>${v.total}</td>
                                <td class="bodyNone">`
                if (v.status == 0) {
                    tbody += `
                                    <button type="button" class="btn btn-icon btn-danger" onclick="deleteDetailTransaksi(${v.id})">
                                        <span class="tf-icons bx bx-trash">Hapus</span>
                                    </button>`
                }
                tbody += `</td>
                  </tr>`
            });

            $("#tbodyTransaksiDetail").html(tbody)
        }

        const updateQuantity = (e, id) => {
            let update = barangLoop.find(v => v.id == id)

            if (parseInt(update.stok) < parseInt(e.value)) {
                update.quantity = update.stok
                $(`#quantityEdit_${id}`).val(update.stok)
                responSwalAlert('end', 'error',
                    'Stok tidak mencukupi, jika input melebih stok maka di kembalikan ke jumlah stok')
                return
            } else if (e.value != 0) {
                update.quantity = e.value
            }

        }

        const deleteDetailTransaksi = (id) => {
            barangLoop = barangLoop.filter(v => v.id != id)
            onLoadBarang()
        }


        $(document).ready(function() {
            loadTransaksi()
            $("#formTransaksi").submit(function(e) {
                e.preventDefault()
                saveForm()
            })
        })
    </script>
@endsection
