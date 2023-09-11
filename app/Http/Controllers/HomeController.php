<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function searchTransaksi(Request $request)
    {
        $data = Transaksi::join('transaksi_detail', 'transaksi.id', '=', 'transaksi_detail.transaksi_id')
                         ->join('barang', 'transaksi_detail.barang_id', '=', 'barang.id')
                         ->join('jenis_barang', 'barang.jenis_barang_id', '=', 'jenis_barang.id')
                         ->selectRaw('SUM(transaksi_detail.quantity) as jumlah_terjual, barang.stok, barang.nama_barang, jenis_barang.nama as jenis_barang, transaksi.tanggal_transaksi')
                         ->where('transaksi.nomor_transaksi', 'like', '%' . $request->search . '%')
                         ->orWhere('barang.nama_barang', 'like', '%' . $request->search . '%');
                        //mengurutkan
                         $request->column == 0 ?  $data->orderBy('barang.nama_barang', 'ASC') : $data->orderBy('transaksi.tanggal_transaksi', 'ASC');

        $transaksi = $data->groupBy('transaksi.tanggal_transaksi', 'barang.stok', 'barang.nama_barang', 'jenis_barang.nama')
                          ->get();

        return response()->json($transaksi);
    }
}
