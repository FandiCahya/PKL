<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'blocked_date',
        'time_slot_id',
        'reason'
    ];

    public function logs()
    {
        return $this->hasMany(Logs::class);
    }
    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }
}
