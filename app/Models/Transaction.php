<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Item;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'jumlah_terjual', 'tanggal_transaksi'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
