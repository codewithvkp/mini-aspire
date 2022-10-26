<?php

namespace App\Providers;

use App\Actions\LoanApplication\CreateLoanRepaymentRecords;
use App\Events\LoanApplication\LoanApplicationApproved;
use App\Events\LoanApplication\LoanApplicationUpdated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        LoanApplicationApproved::class => [
            CreateLoanRepaymentRecords::class,
        ],
        LoanApplicationUpdated::class => [
            CreateLoanRepaymentRecords::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
