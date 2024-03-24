<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheduler extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id', 'start_date',
        'end_date', 'start_time',
        'end_time', 'semester'
    ];

    public function session(){
        return $this->hasOne(Session::class, 'id', 'session_id');
    }
}
