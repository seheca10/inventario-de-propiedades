<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PqrstLog extends Model
{
    use HasFactory;

    protected $table = 'pqrst_logs';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'event_type',
        'description',
        'old_values',
        'new_values',
        'metadata',
        'source',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
    ];

    public function ticket()
    {
        return $this->belongsTo(PqrsTicket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
