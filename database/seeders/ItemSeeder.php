<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create(['nama_barang' => 'Kopi', 'stok' => 100, 'jenis_barang' => 'Konsumsi']);
        Item::create(['nama_barang' => 'Teh', 'stok' => 100, 'jenis_barang' => 'Konsumsi']);
        Item::create(['nama_barang' => 'Pasta Gigi', 'stok' => 100, 'jenis_barang' => 'Pembersih']);
        Item::create(['nama_barang' => 'Sabun Mandi', 'stok' => 100, 'jenis_barang' => 'Pembersih']);
        Item::create(['nama_barang' => 'Sampo', 'stok' => 100, 'jenis_barang' => 'Pembersih']);
    }
}
