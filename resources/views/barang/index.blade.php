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
        @slot('idform')
            formBarang
        @endslot
        @slot('addForm')
            <form id="formBarang">
                <input type="hidden" id="idBarang" name="idBarang">

            </form>
        @endslot
    @endcomponent
@endsection
@section('scripts')
    <script>
        const url = '/barang'

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

        const saveForm = () => {
            removeXhr()

            let id = $("#idBarang").val()
            let urls = id ? `${url}/${id}?_method=PATCH` : `${url}`

            $.ajax({
                type: "POST",
                url: urls,
                data: $("#formBarang").serialize(),
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
            $("#formBarang").submit(function(e) {
                e.preventDefault()
                saveForm()
            })
        })
    </script>
@endsection
