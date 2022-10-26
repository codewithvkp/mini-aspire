<?php

namespace App\Actions\LoanApplication;

use App\Actions\LoanRepayment\CalculateInterest;
use App\Events\LoanApplication\LoanApplicationApproved;
use App\Events\LoanApplication\LoanApplicationUpdated;
use App\Models\LoanApplication;
use App\Models\LoanRepayment;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateLoanRepaymentRecords
{
    use AsAction;

    /**
     * Create loan repayment records based on loan term and period
     *
     * @param LoanApplication $loanApplication
     * @return bool
     * @throws Exception
     */
    public function handle(LoanApplication $loanApplication)
    {

        // Ignore if application is not approved

        if (!$loanApplication->isApproved()) {
            return false;
        }

        // Delete previous records first

        LoanRepayment::query()->where('loan_application_id', $loanApplication->id)->delete();

        // Calculate term end date

        $termEnd = now()->addMonths($loanApplication->term);

        // Get the difference in weeks between tomorrow and term end

        $weeks = now()->addDay()->startOfDay()->diffInWeeks($termEnd->endOfDay());

        // Initiate payment start date (which is tomorrow)

        $paymentStartsOn = now()->addDay()->startOfDay();

        // Calculate repay amount for each week

        $repayAmount = round($loanApplication->amount / $weeks, 2);

        // Create repayment records with calculated data

        foreach (range(1, $weeks) as $week) {
            $loanRepayment = $loanApplication->repayments()->create([
                'payment_date' => $paymentStartsOn->format('Y-m-d'),
                'week' => $week,
                'amount' => $repayAmount,
            ]);
            CalculateInterest::run($loanRepayment);
            $paymentStartsOn->addWeek();
        }

        return true;
    }

    /**
     * Use this action as listener for LoanApplication events
     *
     * @param LoanApplicationApproved|LoanApplicationUpdated $event
     * @throws Exception
     */
    public function asListener(LoanApplicationApproved | LoanApplicationUpdated $event): void
    {
        $this->handle($event->loanApplication);
    }
}
