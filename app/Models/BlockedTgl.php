<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedTgl extends Model
{
    use HasFactory;

    protected $fillable = [
        'blocked_date',
        'reason'
    ];

    public function logs()
    {
        return $this->hasMany(Logs::class);
    }
}
