<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'faculty'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function getSessionCoursesAttribute(){
        return Course::currentSession()->where('department_id', $this->id)->get();
    }
}
