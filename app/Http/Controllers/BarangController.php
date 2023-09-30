<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
                             ->addColumn('foto_barang', function ($row) {
                                 $img = 'storage/barang/'.$row->foto_barang;
                                 return '<img class="card-img-top" style="border-radius: 10%;"  width="50px" height="50px" src="'. (file_exists($img) && !empty($row->foto_barang) ? asset($img) : asset('no_image.png') ).'" alt="Card image cap">';
                             })
                             ->addColumn('jenis_barang', function ($row) {
                                 return $row->jenisBarang->nama;
                             })
                             ->addColumn('action', 'barang.action')
                             ->rawColumns(['action', 'foto_barang'])
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
            'foto_barang' => ['required','image','mimes:jpeg,png,jpg','max:2048'],
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('foto_barang')) {
                $foto = $request->file('foto_barang');
                $fileUpload = time().".".$foto->getClientOriginalExtension();
                $foto->storeAs('public/barang', $fileUpload);
            }

            Barang::create([
                'nama_barang' => $request->namaBarang,
                'jenis_barang_id' => $request->jenisBarangId,
                'stok' => $request->stokBarang,
                'harga' => $request->hargaBarang,
                'foto_barang' => $fileUpload
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

        if ($request->hasFile('foto_barang')) {
            $request->validate([
                'foto_barang' => ['required','image','mimes:jpeg,png,jpg','max:2048'],
            ]);
        }

        $fileOld = $barang->foto_barang;

        try {
            DB::beginTransaction();

            if ($request->hasFile('foto_barang')) {
                $foto = $request->file('foto_barang');
                $fileUpload = time().".".$foto->getClientOriginalExtension();

                Storage::delete('public/barang/'.$fileOld);
                $foto->storeAs('public/barang', $fileUpload);
            }

            $barang->update([
                'nama_barang' => $request->namaBarang,
                'jenis_barang_id' => $request->jenisBarangId,
                'stok' => $request->stokBarang,
                'harga' => $request->hargaBarang,
                'foto_barang' => !$request->file('foto_barang') ? $fileOld : $fileUpload,
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

            Storage::delete('public/barang/'.$barang->foto_barang);

            $barang->delete();

            DB::commit();
            $respons = ['status' => 200,  'message' => 'berhasil hapus barang', 'data' => []];
        } catch (\Throwable $th) {
           DB::rollBack();
           $respons = ['status' => 500,  'message' => $th->getMessage(), 'data' => []];
        }
        return response()->json($respons);
    }

    public function getAllBarang(Request $request)
    {
        $data = Barang::with(['jenisBarang']);
                        if ($request->jenis_id) {
                            $data->where('jenis_barang_id', $request->jenis_id);
                        }
        $barang = $data->latest('id')
                        ->get();
        return response()->json($barang);
    }
}
