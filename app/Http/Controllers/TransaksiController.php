<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaksi::latest('id')->get();
            return DataTables::of($data)
                            ->addIndexColumn()
                            ->addColumn('action', 'transaksi.action')
                            ->rawColumns(['action'])
                            ->toJson();
        }
        return view('transaksi.index');
    }

    public function store(Request $request)
    {
        $check = collect($request->transaksiBarang)->filter(function ($res) {
             return Barang::where('id', $res['id'])->where('stok', '<', $res['quantity'])->first();

        })->count();

        if ($check != 0) return response()->json(['status' => 400, 'message' => 'Terdapat barang yang Stok tidak mencukupi', 'data' => []]);

        try {
            DB::beginTransaction();


            $total = 0;
            $total = collect($request->transaksiBarang)->sum('total');

            $id = Transaksi::create([
                'nomor_transaksi' => invoice(),
                'tanggal_transaksi' => Carbon::now()->format('Y-m-d'),
                'total' => $total
            ]);

            foreach ($request->transaksiBarang as $key => $value) {
                Barang::where('id', $value['id'])->decrement('stok', $value['quantity']);

                TransaksiDetail::insert([
                    'transaksi_id' => $id->id,
                    'barang_id' => $value['id'],
                    'quantity' => $value['quantity'],
                    'harga' => $value['harga'],
                    'total' => $value['total'],
                    'created_at' => Carbon::now(),
                ]);
            }

            DB::commit();
            $respons = ['status' => 200,  'message' => 'berhasil membuat transaksi', 'data' => []];
        } catch (\Throwable $th) {
           DB::rollBack();
           $respons = ['status' => 500,  'message' => $th->getMessage(), 'data' => []];
        }
        return response()->json($respons);
    }

    public function show($id)
    {
        $data = Transaksi::with(['transaksiDetail.barang.jenisBarang'])->where('id', $id)->first();
        return response()->json($data);
    }
}
