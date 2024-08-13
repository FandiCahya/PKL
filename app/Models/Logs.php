<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'table_name',
        'table_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array', 
    ];

    /**
     * Get the user that owns the log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function instruktur()
    {
        return $this->belongsTo(Instruktur::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function bookings()
    {
        return $this->belongsTo(Booking::class);
    }

    public function blockdate()
    {
        return $this->belongsTo(BlockedDate::class);
    }
}
