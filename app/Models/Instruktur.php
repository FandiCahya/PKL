<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'email',
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
