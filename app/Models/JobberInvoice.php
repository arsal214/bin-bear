<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobberInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jobber_invoice_id',
        'jobber_job_id',
        'invoice_number',
        'total',
        'status',
        'payment_url',
        'public_url',
        'payment_amount',
        'paid_at',
        'metadata',
    ];

    protected $casts = [
        'total' => 'integer',
        'payment_amount' => 'integer',
        'paid_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getTotalInDollarsAttribute()
    {
        return $this->total / 100;
    }

    public function getPaymentAmountInDollarsAttribute()
    {
        return $this->payment_amount ? $this->payment_amount / 100 : null;
    }

    public function isPaid(): bool
    {
        return in_array($this->status, ['paid', 'fully_paid']);
    }

    public function isPending(): bool
    {
        return in_array($this->status, ['sent', 'viewed']);
    }
}