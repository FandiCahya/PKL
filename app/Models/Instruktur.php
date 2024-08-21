<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instruktur extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'email',
    ];



    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function logs()
    {
        return $this->hasMany(Logs::class);
    }
}
