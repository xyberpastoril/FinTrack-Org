<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ESolution\DBEncryption\Traits\EncryptedAttribute;

class Student extends Model
{
    use HasFactory, EncryptedAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_number',
        'last_name',
        'first_name',
        'middle_name',
    ];

    /**
     * The attributes that should be encrypted on save.
     *
     * @var array
     */
    protected $encryptable = [
        'id_number',
        'last_name',
        'first_name',
        'middle_name',
    ];

    public function enrolledSemesters()
    {
        return $this->hasMany(EnrolledStudent::class);
    }

    public function getName()
    {
        return $this->last_name . ', ' . $this->first_name . ' ' . $this->middle_name;
    }
}
