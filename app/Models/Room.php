<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['nama', 'kapasitas', 'availability', 'harga'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function logs()
    {
        return $this->hasMany(Logs::class);
    }

    public function reduceCapacity()
    {
        $this->kapasitas--;
        if ($this->kapasitas <= 0) {
            $this->availability = false;
        } else {
            $this->availability = true;
        }

        if ($this->kapasitas > 0) {
            $this->availability = true;
        }
        $this->save();
    }
}
