<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ESolution\DBEncryption\Traits\EncryptedAttribute;

class Transaction extends Model
{
    use HasFactory, EncryptedAttribute;

    protected $fillable = [
        'semester_id',
        'receipt_id',
        'date',
        'category',
        'type',
        'description',
        'amount',
        'foreign_key_id',
        'logged_by_user_id',
    ];

    /**
     * The attributes that should be encrypted on save.
     *
     * @var array
     */
    protected $encryptable = [
        'description',
    ];
}
