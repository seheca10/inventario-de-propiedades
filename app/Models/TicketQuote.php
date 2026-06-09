<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TicketQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'contractor_id',
        'access_token',
        'description',
        'labor_cost',
        'material_cost',
        'total_amount',
        'status',
        'rejection_reason',
        'pdf_path',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'approved_at'   => 'datetime',
        'labor_cost'    => 'decimal:2',
        'material_cost' => 'decimal:2',
        'total_amount'  => 'decimal:2',
    ];

    protected static function boot(): void
    {
        parent::boot();
 
        // Genera el access_token una sola vez al crear la cotización.
        // Este token es el que va en el email al propietario para
        // aprobar/rechazar sin necesidad de login.
        static::creating(function (TicketQuote $quote) {
            $quote->access_token = Str::random(64);
            $quote->status       = $quote->status ?? 'pending';
        });
    }

    public function ticket()
    {
        return $this->belongsTo(PqrsTicket::class);
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'Pendiente';
            case 'approved':
                return 'Aprobada';
            case 'rejected':
                return 'Rechazada';
            default:
                return ucfirst($this->status);
        }
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------
 
    /**
     * URL pública para que el propietario revise y apruebe la cotización.
     * No requiere login — usa el access_token como autenticación.
     */
    public function getOwnerReviewUrlAttribute(): string
    {
        return route('owner.review-quote', [
            'ticket' => $this->ticket_id,
            'token'  => $this->access_token,
        ]);
    }
 
    /**
     * URL pública para rechazo directo desde el email.
     */
    public function getOwnerRejectUrlAttribute(): string
    {
        return route('owner.reject-quote', [
            'ticket' => $this->ticket_id,
            'token'  => $this->access_token,
        ]);
    }
 
    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'approved';
    }
 
    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }
 
    public function getIsRejectedAttribute(): bool
    {
        return $this->status === 'rejected';
    }
}