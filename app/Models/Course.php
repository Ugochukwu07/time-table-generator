<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'department_id', 'session_id', 'students', 'duration'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function department(){
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function scopeCurrentSession($query){
        $session = Session::active()->first();
        return $query->where('session_id', $session->id);
    }
}
