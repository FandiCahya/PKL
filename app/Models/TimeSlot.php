<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeSlot extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'start_time',
        'end_time',
        'room_id', // Add `room_id`
        'availability',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function blockedDates()
    {
        return $this->hasMany(BlockedDate::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
