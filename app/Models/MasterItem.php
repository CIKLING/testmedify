<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'master_items';

    protected $fillable = [
        'kode',
        'nama',
        'jenis',
        'harga_beli',
        'laba',
        'supplier',
        'foto'  // ← TAMBAH INI untuk upload foto
    ];

    // ← TAMBAH RELASI INI untuk kategori (many-to-many)
    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'kategori_master_item');
    }

    // ← TAMBAH ACCESSOR INI untuk harga jual
    public function getHargaJualAttribute()
    {
        return $this->harga_beli + ($this->harga_beli * $this->laba / 100);
    }
}