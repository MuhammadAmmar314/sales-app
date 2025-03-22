<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Item;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            ['item_id' => 1, 'jumlah_terjual' => 10, 'tanggal_transaksi' => '2021-05-01'],
            ['item_id' => 2, 'jumlah_terjual' => 19, 'tanggal_transaksi' => '2021-05-05'],
            ['item_id' => 1, 'jumlah_terjual' => 15, 'tanggal_transaksi' => '2021-05-10'],
            ['item_id' => 3, 'jumlah_terjual' => 20, 'tanggal_transaksi' => '2021-05-11'],
            ['item_id' => 4, 'jumlah_terjual' => 30, 'tanggal_transaksi' => '2021-05-11'],
            ['item_id' => 5, 'jumlah_terjual' => 25, 'tanggal_transaksi' => '2021-05-12'],
            ['item_id' => 2, 'jumlah_terjual' => 5, 'tanggal_transaksi' => '2021-05-12']
        ];

        foreach($transactions as $transaction){
            $item = Item::find($transaction['item_id']);

            if($item && $item->stok >= $transaction['jumlah_terjual']){
                Transaction::create([
                    'item_id' => $transaction['item_id'],
                    'jumlah_terjual' => $transaction['jumlah_terjual'],
                    'tanggal_transaksi' => $transaction['tanggal_transaksi']
                ]);
                
                $item->stok -= $transaction['jumlah_terjual'];
                $item->save();
            }
        }
    }
}
