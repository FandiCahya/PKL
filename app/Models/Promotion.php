<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'deskripsi',
        'tgl',
        'waktu',
        'harga'
    ];

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function logs()
    {
        return $this->hasMany(Logs::class);
    }
}
