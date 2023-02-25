<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'semester',
    ];

    public function enrolledStudents()
    {
        return $this->hasMany(EnrolledStudent::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }
}
