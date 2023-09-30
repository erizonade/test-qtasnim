@extends('layouts.app')
@section('content')
    <div class="row mx-2">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4>Produk</h4>
                    <div class="card-tools">

                        <select name="jenis_id" id="jenis_id" onchange="onloadBarang(this.value)" class="form-control">
                            <option value="">Pilih Jenis Barang</option>
                            @foreach ($jenis as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row" id="produk-all">
                    </div>

                    <div class="row justify-content-center " id="pagination">
                        {{-- {{ $paginator->links() }} --}}
                    </div>

                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4>Cart</h4>
                        <div class="text-right">
                            <h3 id="nomorInvoice">{{ invoice() }}</h3>
                            <p>Tanggal Transaksi : {{ now()->toDateString() }}</p>
                        </div>
                    </div>

                </div>
                <div class="card-body">

                    <table class="table table-striped table-md">
                        <thead>
                            <tr>
                                <th width="15%">Jenis Barang</th>
                                <th width="25%">Barang</th>
                                <th width="10%" style="text-align: center">Stok</th>
                                <th width="10%" style="text-align: center">Quantity</th>
                                <th width="10%" style="text-align: center">Harga</th>
                                <th width="10%" style="text-align: center">Total</th>
                                <th width="10%"><i class="fas fa-trash"></i></th>
                            </tr>
                        </thead>
                        <tbody id="detail-produk">
                        </tbody>

                        <tfoot>
                            <tr>
                                <td>
                                    <div class="d-flex">
                                        <span class="mr-3">Total Produk &nbsp;</span>
                                        <span  id="total-produk"> 0</span>
                                    </div>
                                </td>
                                <td colspan="3"></td>
                                <td>Total Bayar</td>
                                <td class="total-akhir">0</td>
                                <td ></td>
                            </tr>

                        </tfoot>
                    </table>
                    <div class="row float-right">

                        <form id="formTransaksi">
                            <div class="col-12">
                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="">Bayar</label>
                                        <input type="text" name="bayar" oninput="validateInput(this)" min="0" onkeyup="bayarOrder(event, this.value)" id="bayar" class="form-control text-right">
                                    </div>


                                    <label for="">Kembalian</label>
                                    <input type="text" disabled name="kembalian" id="kembalian" class="form-control">

                                </div>
                                <div class="card-body">
                                    <button type="submit" form="formTransaksi" class="btn btn-success btn-login form-control formTransaksi">Proses</button>
                                    <div class="d-flex justify-content-center d-none loading"><i class="fas fa-spinner fa-2x fa-spin"></i></div>

                                </div>

                            </div>
                        </form>


                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const onloadBarang = (id) => {
            $.ajax({
                type: "GET",
                url: "/barang/getAllBarang",
                data: {
                    jenis_id: id
                },
                beforeSend : function () {
                    myswalloading()
                },
                success: function(response) {
                    Swal.close()

                    let option = ''
                    response.forEach(val => {
                        option +=`<div class="col-md-4">
                                    <div class="card shadow-sm p-1  bg-white rounded" style="margin-top: 5%;">
                                        <img class="card-img-top" style="border-radius: 10%;"  width="100px" height="150px" onerror="this.onerror=null; this.src='no_image.png'" src="storage/barang/${val.foto_barang}" alt="Card image cap">
                                        <div class="card-body">
                                        <span style="font-size: 12px">${val.nama_barang} (Stok ${val.stok})</span>
                                        <br>
                                        <b class="card-link">Rp. ${val.harga}</b>
                                        <b class="card-link float-right"></b>
                                        </div>

                                        <button class="btn btn-outline-primary addCart" data-barang="${val.harga}|${val.jenis_barang.nama}|${val.stok}|${val.nama_barang}" data-id="${val.id}" onclick="addTransaksiDetail(event, this)">+ AddCart</button>
                                    </div>

                                </div>`
                    })

                    $("#produk-all").html(option)
                }
            });
        }

        let barangLoop = []
        const addTransaksiDetail = async (event, thisdata) => {
            let barangId = $(thisdata).data("id")
            const [harga, jenis_barang, stok, nama_barang] = $(thisdata).data("barang").split("|")

            const check = barangLoop.find(v => v.id == barangId)
            if (check) {
                let quantity  = 1 + check.quantity
                if (parseInt(check.stok) < parseInt(quantity)) {
                    responSwalAlert('end', 'error','Stok tidak mencukupi, jika input melebih stok maka di kembalikan ke jumlah stok')
                    return
                } else if (quantity != 0) {
                    check.quantity = quantity
                }
            } else {
                barangLoop.push({
                    id: barangId,
                    quantity: 1,
                    harga: harga,
                    total: parseInt(1 + harga),
                    jenis_barang: jenis_barang,
                    stok: stok,
                    status : 0,
                    nama_barang: nama_barang
                })

            }
            await onLoadCart()
        }

        $("#formTransaksi").submit(function (e) {
            e.preventDefault()
            saveForm()
        })

        const saveForm = () => {
            removeXhr()

            let sum   = barangLoop.reduce((v, i) => v + parseFloat(i.total), 0)
            let bayar = parseInt($("#bayar").val())

            if (bayar < sum) {
                responSwalAlert('end', 'warning', 'Bayar kurang dari total transaksi')
                return
            }

            if (barangLoop.length < 1) return responSwalAlert('end', 'warning', 'Cart kosong')

            $.ajax({
                type: "POST",
                url: '/transaksi',
                data: {
                    transaksiBarang: barangLoop
                },
                beforeSend: function() {
                    $(".loading").removeClass("d-none")
                    $(".formTransaksi").addClass("d-none")
                },
                error: function(xhr) {
                    handleErrorXhr(xhr)
                },
                success: function(response) {
                    if (response.status == 200) {
                        $(".loading").addClass("d-none")
                        $(".formTransaksi").removeClass("d-none")

                        responSwalAlert('end', 'success', response.message)
                        loadInvoice()

                        barangLoop = []
                        onLoadCart()

                        $("#bayar").val(0)
                        $("#kembalian").val(0)

                    } else {
                        responSwalAlert('end', 'error', response.message)

                        $(".loading").addClass("d-none")
                        $(".formTransaksi").removeClass("d-none")
                    }
                },
            });
        }

        const bayarOrder = (event, value) => {
            let sum  = barangLoop.reduce((v, i) => v + parseFloat(i.total), 0)

            let kembalian = 0
            if (value > sum) kembalian = value - sum
            if (sum > value) kembalian = 0

            $("#kembalian").val(kembalian)

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
                $(`.total_${update.id}`).html(parseInt(update.quantity) * parseInt(update.harga))
            }

        }

        const deleteDetailTransaksi = (id) => {
            barangLoop = barangLoop.filter(v => v.id != id)
            onLoadCart()
        }

        const onLoadCart = () => {
            let tbody = ''
            barangLoop.forEach((v, i) => {
                let quantity = ''
                if (v.status == 0) {
                    quantity =
                        `<input type="text" reqeuired oninput="validateInput(this)" min="0" id="quantityEdit_${v.id}" name="quantityEdit"
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
                                <td class="total_${v.id}">${v.total}</td>
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

            $("#detail-produk").html(tbody)
            $("#total-produk").html(barangLoop.length)
            $(".total-akhir").html(barangLoop.reduce((v, i) => v + parseFloat(i.total), 0))
        }

        const loadInvoice = () => {
            $.ajax({
                type: "GET",
                url: "/transaksi/invoice",
                success: function(response) {
                    $("#nomorInvoice").html(response)
                }
            })
        }

        $(function () {
            onloadBarang(null)
        })
    </script>
@endsection
