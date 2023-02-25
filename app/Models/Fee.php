<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ESolution\DBEncryption\Traits\EncryptedAttribute;

class Fee extends Model
{
    use HasFactory, EncryptedAttribute;

    protected $fillable = [
        'semester_id',
        'name',
        'amount',
        'is_required',
    ];

    /**
     * The attributes that should be encrypted on save.
     *
     * @var array
     */
    protected $encryptable = [
        'name',
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }


}
