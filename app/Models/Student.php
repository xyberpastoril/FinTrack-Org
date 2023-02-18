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
        'degree_program_id',
        'id_number',
        'last_name',
        'first_name',
        'year_level',
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
    ];

    public function degreeProgram()
    {
        return $this->belongsTo(DegreeProgram::class);
    }

    public function eventLogs()
    {
        return $this->hasMany(EventLog::class);
    }
}
