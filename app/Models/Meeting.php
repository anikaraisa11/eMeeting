<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{

    use HasFactory;

    public function attendees()
    {
        return $this->hasMany(Attendee::class,'meeting_id','id');
    }
}
