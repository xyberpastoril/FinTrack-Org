<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrolled_student_id',
        'date',
        'logged_by_user_id',
    ];

    public function enrolledStudent()
    {
        return $this->belongsTo(EnrolledStudent::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
