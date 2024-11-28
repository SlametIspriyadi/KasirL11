<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Livewire\Transaksi;
use App\Models\Produk;

class DetilTransaksi extends Model
{
    protected $fillable = ['transaksi_id', 'produk_id', 'jumlah'];
    public function transaksi(){

        return $this->belongsTo(Transaksi::class);
    }
    public function produk(){

        return $this->belongsTo(Produk::class);
    }
}
