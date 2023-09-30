<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $jenis = JenisBarang::all();
        return view('home', compact('jenis'));
    }

    public function searchTransaksi(Request $request)
    {
        if ($request->ajax()) {
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

        return view('report.result_transaksi');
    }

    public function filterTransaksi(Request $request)
    {
        if ($request->ajax()) {
            $startDate = $request->startDate ?? Carbon::now()->format('Y-m-d');
            $endDate   = $request->endDate ?? Carbon::now()->format('Y-m-d');

            $data = JenisBarang::join('barang', 'jenis_barang.id', '=', 'barang.jenis_barang_id')
                            ->leftJoin('transaksi_detail', 'barang.id', '=', 'transaksi_detail.barang_id')
                            ->leftJoin('transaksi', 'transaksi_detail.transaksi_id', '=', 'transaksi.id')
                            ->selectRaw('SUM(transaksi_detail.quantity) as jumlah_terjual, jenis_barang.nama as jenis_barang')
                            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
                            ->orderBy('jumlah_terjual', 'DESC')
                            ->groupBy('jenis_barang.nama')
                            ->get();
            return response()->json($data);
        }

        return view('report.perbandingan_transaksi');
    }
}
