<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'kode',
        'nama'
    ];

    public function masterItems()
    {
        return $this->belongsToMany(MasterItem::class, 'kategori_master_item');
    }
}