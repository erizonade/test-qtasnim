@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3>Jenis Barang</h3>
                        <button class="btn btn-sm btn-info" onclick="addModal()">Tambah</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover" id="tbJenisBarang">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
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
            jenisBarang
        @endslot
        @slot('title')
            Jenis Barang
        @endslot
        @slot('idform')
            formJenisBarang
        @endslot
        @slot('addForm')
            <form id="formJenisBarang">
                <input type="hidden" id="idJenisBarang" name="idJenisBarang">
                <div class="mb-3">
                    <label for="namaJenisBarang" class="form-label">Nama</label>
                    <input type="text" class="form-control" name="namaJenisBarang" id="namaJenisBarang"
                        placeholder="Nama Jenis Barang">
                </div>
            </form>
        @endslot
    @endcomponent
@endsection
@section('scripts')
    <script>
        const url = '/jenis-barang'

        const addModal = () => {
            removeForm()
            $("#jenisBarang").modal("show")
        }

        const editModal = (id) => {
            $("#jenisBarang").modal("show")

            $.ajax({
                type: "GET",
                url: `${url}/${id}/edit`,
                success: function(response) {
                    $("#idJenisBarang").val(response.id)
                    $("#namaJenisBarang").val(response.nama)
                }
            });
        }

        const deleteJenisBarang = (id) => {
            swalConfirmasion('Akan menghapus jenis barang?', () => {
                $.ajax({
                    type: "DELETE",
                    url: `${url}/${id}`,
                    error: function(xhr) {
                        handleErrorXhr(xhr)
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            loadJenisBarang()
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

            let id = $("#idJenisBarang").val()
            let urls = id ? `${url}/${id}?_method=PATCH` : `${url}`

            $.ajax({
                type: "POST",
                url: urls,
                data: {
                    namaJenisBarang: $("#namaJenisBarang").val()
                },
                error: function(xhr) {
                    handleErrorXhr(xhr)
                },
                success: function(response) {
                    if (response.status == 200) {
                        $("#jenisBarang").modal("hide")
                        loadJenisBarang()
                        responSwalAlert('end', 'success', response.message)
                    } else {
                        responSwalAlert('end', 'error', response.message)
                    }
                }
            });
        }

        const loadJenisBarang = () => {
            $("#tbJenisBarang").DataTable({
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
                        data: 'nama',
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
            loadJenisBarang()
            $("#formJenisBarang").submit(function(e) {
                e.preventDefault()
                saveForm()
            })
        })
    </script>
@endsection
