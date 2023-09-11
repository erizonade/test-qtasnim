<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Barang::with(['jenisBarang'])->latest('id')->get();
            return DataTables::of($data)
                             ->addIndexColumn()
                             ->addColumn('jenis_barang', function ($row) {
                                 return $row->jenisBarang->nama;
                             })
                             ->addColumn('action', 'barang.action')
                             ->toJson();
        }

        $jenisBarang = JenisBarang::all();

        return view('barang.index', compact('jenisBarang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'namaBarang' => ['required', 'unique:barang,nama_barang'],
            'jenisBarangId' => ['required'],
            'hargaBarang' => ['required', 'numeric'],
            'stokBarang' => ['required', 'numeric'],
        ]);

        try {
            DB::beginTransaction();

            Barang::create([
                'nama_barang' => $request->namaBarang,
                'jenis_barang_id' => $request->jenisBarangId,
                'stok' => $request->stokBarang,
                'harga' => $request->hargaBarang
            ]);

            DB::commit();
            $respons = ['status' => 200,  'message' => 'berhasil tambah barang', 'data' => []];
        } catch (\Throwable $th) {
           DB::rollBack();
           $respons = ['status' => 500,  'message' => $th->getMessage(), 'data' => []];
        }
        return response()->json($respons);
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        return response()->json($barang);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'namaBarang' => ['required', 'unique:barang,nama_barang,'.$barang->id.',id'],
            'jenisBarangId' => ['required'],
            'hargaBarang' => ['required', 'numeric'],
            'stokBarang' => ['required', 'numeric'],
        ]);

        try {
            DB::beginTransaction();

            $barang->update([
                'nama_barang' => $request->namaBarang,
                'jenis_barang_id' => $request->jenisBarangId,
                'stok' => $request->stokBarang,
                'harga' => $request->hargaBarang
            ]);

            DB::commit();
            $respons = ['status' => 200,  'message' => 'berhasil update barang', 'data' => []];
        } catch (\Throwable $th) {
           DB::rollBack();
           $respons = ['status' => 500,  'message' => $th->getMessage(), 'data' => []];
        }
        return response()->json($respons);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {

        try {
            DB::beginTransaction();

            $barang->delete();

            DB::commit();
            $respons = ['status' => 200,  'message' => 'berhasil hapus barang', 'data' => []];
        } catch (\Throwable $th) {
           DB::rollBack();
           $respons = ['status' => 500,  'message' => $th->getMessage(), 'data' => []];
        }
        return response()->json($respons);
    }

    public function getAllBarang()
    {
        $data = Barang::with(['jenisBarang'])->latest('id')->get();
        return response()->json($data);
    }
}
