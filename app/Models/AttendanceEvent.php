<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'date',
        'status',
        'required_logs',
        'fines_amount_per_log',
    ];

    /**
     * The attributes that should be encrypted on save.
     *
     * @var array
     */
    protected $encryptable = [
        'name',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function logs()
    {
        return $this->hasMany(AttendanceEventLog::class);
    }
}
