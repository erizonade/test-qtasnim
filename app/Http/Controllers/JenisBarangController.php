<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JenisBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = JenisBarang::latest('id')->get();
            return DataTables::of($data)
                             ->addIndexColumn()
                             ->addColumn('action', 'jenis_barang.action')
                             ->toJson();
        }

        return view('jenis_barang.index');
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
        $request->validate(['namaJenisBarang' => ['required', 'unique:jenis_barang,nama']]);
        try {
            DB::beginTransaction();

            JenisBarang::create([
                'nama' => $request->namaJenisBarang
            ]);

            DB::commit();
            $respons = ['status' => 200,  'message' => 'berhasil tambah jenis barang', 'data' => []];
        } catch (\Throwable $th) {
           DB::rollBack();
           $respons = ['status' => 500,  'message' => $th->getMessage(), 'data' => []];
        }
        return response()->json($respons);
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisBarang $jenisBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisBarang $jenisBarang)
    {
        return response()->json($jenisBarang);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisBarang $jenisBarang)
    {
        $request->validate(['namaJenisBarang' => ['required', 'unique:jenis_barang,nama,'.$jenisBarang->id.',id']]);
        try {
            DB::beginTransaction();

            $jenisBarang->update([
                'nama' => $request->namaJenisBarang
            ]);

            DB::commit();
            $respons = ['status' => 200,  'message' => 'berhasil update jenis barang', 'data' => []];
        } catch (\Throwable $th) {
           DB::rollBack();
           $respons = ['status' => 500,  'message' => $th->getMessage(), 'data' => []];
        }
        return response()->json($respons);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisBarang $jenisBarang)
    {

        try {
            DB::beginTransaction();

            $jenisBarang->delete();

            DB::commit();
            $respons = ['status' => 200,  'message' => 'berhasil hapus jenis barang', 'data' => []];
        } catch (\Throwable $th) {
           DB::rollBack();
           $respons = ['status' => 500,  'message' => $th->getMessage(), 'data' => []];
        }
        return response()->json($respons);
    }
}
