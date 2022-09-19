<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'status',
    ];

    public const PENDING = 0;
    public const PAID = 1;

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
