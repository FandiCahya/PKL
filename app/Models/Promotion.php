<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'deskripsi',
        'tgl',
        'waktu',
        'harga',
        'room_id',
        'instruktur_id'
    ];



    public function instruktur()
    {
        return $this->belongsTo(Instruktur::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
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
