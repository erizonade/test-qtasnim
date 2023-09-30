<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        JenisBarang::insert([
            [
                'nama' => 'Konsumsi',
            ],
            [
                'nama' => 'Pembersih',
            ]
        ]);

        Barang::insert([
            [
                'nama_barang' => 'Kopi',
                'jenis_barang_id' => 1,
                'stok' => 10,
                'harga' => 10000
            ],
            [
                'nama_barang' => 'Teh',
                'jenis_barang_id' => 1,
                'stok' => 10,
                'harga' => 10000
            ],
            [
                'nama_barang' => 'Pasta Gigi',
                'jenis_barang_id' => 2,
                'stok' => 10,
                'harga' => 10000
            ],
            [
                'nama_barang' => 'Sabun Mandi',
                'jenis_barang_id' => 2,
                'stok' => 10,
                'harga' => 10000
            ],
            [
                'nama_barang' => 'Sampo',
                'jenis_barang_id' => 2,
                'stok' => 10,
                'harga' => 10000
            ],
        ]);

        User::insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123123')
        ]);

    }
}
