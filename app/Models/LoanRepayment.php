<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'payment_date',
        'week',
        'paid_at',
        'amount',
        'interest_amount',
        'interest',
        'total_amount',
        'loan_application_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var string[]
     */
    protected $casts = [
        'paid_at' => 'datetime',
    ];

    /**
     * LoanApplication relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    /**
     * Determine if this repayment has been paid or not
     *
     * @return bool
     */
    public function isPaid()
    {
        return !empty($this->paid_at);
    }

    /**
     * Query scope to filter paid repayments
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePaid(Builder $query)
    {
        return $query->whereNotNull('paid_at');
    }

    /**
     * Query scope to filter pending applications
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePending(Builder $query)
    {
        return $query->whereNull('paid_at');
    }
}
