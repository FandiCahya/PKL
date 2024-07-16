<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotions_id',
        'instrukturs_id',
        'rooms_id',
        'tgl',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotions_id');
    }

    public function instruktur()
    {
        return $this->belongsTo(Instruktur::class, 'instrukturs_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'rooms_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function logs()
    {
        return $this->hasMany(Logs::class);
    }
}
