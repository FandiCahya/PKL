<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockedTgl extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'blocked_date',
        'reason'
    ];

    public function logs()
    {
        return $this->hasMany(Logs::class);
    }
}
