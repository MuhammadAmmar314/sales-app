<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Transaction;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['nama_barang', 'stok', 'jenis_barang'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
