<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'amount',
        'term',
        'term_period',
        'approved_by',
        'approved_at',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var string[]
     */
    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * User relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ApprovedBy relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Determine if the loan application has been approved or not
     *
     * @return bool
     */
    public function isApproved()
    {
        return (boolean) !empty($this->approved_at);
    }

    /**
     * Query scope to filter approved applications
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeApproved(Builder $query)
    {
        return $query->whereNotNull('approved_at');
    }

    /**
     * Query scope to filter pending applications
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePending(Builder $query)
    {
        return $query->whereNull('approved_at');
    }

    /**
     * Repayments relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function repayments()
    {
        return $this->hasMany(LoanRepayment::class);
    }
}
