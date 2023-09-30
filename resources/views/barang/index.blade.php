@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3>Barang</h3>
                        <button class="btn btn-sm btn-info" onclick="addModal()">Tambah</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover" id="tbBarang">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto Barang</th>
                                <th>Jenis Barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Stok</th>
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
            barang
        @endslot
        @slot('title')
            Barang
        @endslot
        @slot('sizeModal')
            modal-lg
        @endslot
        @slot('idform')
            formBarang
        @endslot
        @slot('addForm')
            <form id="formBarang" enctype="multipart/form-data">
                <input type="hidden" id="idBarang" name="idBarang">

                <div class="mb-3 ">
                    <label for="satuan" class="form-label">Jenis Barang</label>
                    <select name="jenisBarangId" id="jenisBarangId" class="form-control">
                        <option value="">Pilih Jenis Barang</option>
                        @foreach ($jenisBarang as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 ">
                    <label for="namaBarang" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control"  id="namaBarang" name="namaBarang" placeholder="Nama Barang">
                </div>

                <div class="mb-3 ">
                    <label for="hargaBarang" class="form-label">Harga Barang</label>
                    <input type="text" class="form-control"  id="hargaBarang" name="hargaBarang" oninput="validateInput(this)" min="0" placeholder="Harga Barang">
                </div>

                <div class="mb-3 ">
                    <label for="stokBarang" class="form-label">Stok Barang</label>
                    <input type="text" class="form-control"  id="stokBarang" name="stokBarang" oninput="validateInput(this)" min="0" placeholder="Stok Barang">
                </div>

                <div class="mb-3 ">
                    <label for="foto_barang" class="form-label">Foto Barang</label>
                    <input class="form-control"  onchange="showFoto(event)" type="file" id="foto_barang" name="foto_barang">
                </div>
            </form>


            <div class="justify-content-center d-none" id="divShowFoto">
                <img src="" id="showNow" class="img-thumbnail w-50 h-50" alt="" >
            </div>

        @endslot
    @endcomponent
@endsection
@section('scripts')
    <script>
        const url = '/barang'

        const showFoto = (event) => {
            $("#divShowFoto").removeClass('d-none')
            $("#showNow").attr('src', URL.createObjectURL(event.target.files[0]));
        }

        const addModal = () => {
            removeForm()
            $("#barang").modal("show")
        }

        const editModal = (id) => {
            removeXhr()
            $("#barang").modal("show")

            $.ajax({
                type: "GET",
                url: `${url}/${id}/edit`,
                success: function(response) {
                    $("#idBarang").val(response.id)
                    $("#namaBarang").val(response.nama_barang)
                    $("#hargaBarang").val(response.harga)
                    $("#stokBarang").val(response.stok)
                    $("#jenisBarangId").val(response.jenis_barang_id)
                }
            });
        }

        const deleteBarang = (id) => {
            swalConfirmasion('Akan menghapus barang?', () => {
                $.ajax({
                    type: "DELETE",
                    url: `${url}/${id}`,
                    error: function(xhr) {
                        handleErrorXhr(xhr)
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            loadBarang()
                            responSwalAlert('end', 'success', response.message)
                        } else {
                            responSwalAlert('end', 'error', response.message)
                        }
                    }
                })
            })
        }


        $("#formBarang").submit(function (e){
            saveForm(e)
        })

        const saveForm = (event) => {
            event.preventDefault()
            removeXhr()

            let id = $("#idBarang").val()
            let urls = id ? `${url}/${id}?_method=PATCH` : `${url}`

            let form = document.getElementById('formBarang')
            let formData = new FormData(form)

            $.ajax({
                type: "POST",
                url: urls,
                data: formData,
                contentType: false,
                processData: false,
                error: function(xhr) {
                    handleErrorXhr(xhr)
                },
                success: function(response) {
                    if (response.status == 200) {
                        $("#barang").modal("hide")
                        loadBarang()
                        responSwalAlert('end', 'success', response.message)
                    } else {
                        responSwalAlert('end', 'error', response.message)
                    }
                }
            });
        }

        const loadBarang = () => {
            $("#tbBarang").DataTable({
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
                        data: 'foto_barang',
                        className: 'text-left',
                    },
                    {
                        data: 'jenis_barang',
                        className: 'text-left',
                    },
                    {
                        data: 'nama_barang',
                        className: 'text-left',
                    },
                    {
                        data: 'harga',
                        className: 'text-left',
                    },
                    {
                        data: 'stok',
                        className: 'text-left',
                    },
                    {
                        data: 'action',
                        className: 'text-center',
                    }
                ]
            })
        }

        $(document).ready(function() {
            loadBarang()
        })
    </script>
@endsection
