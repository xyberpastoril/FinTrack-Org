<?php

namespace App\Models;

use ESolution\DBEncryption\Traits\EncryptedAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DegreeProgram extends Model
{
    use HasFactory, EncryptedAttribute;

    protected $fillable = [
        'name',
        'abbr',
    ];

    protected $encryptable = [
        'name',
        'abbr',
    ];

    public function enrolledStudents()
    {
        return $this->hasMany(EnrolledStudent::class);
    }
}
