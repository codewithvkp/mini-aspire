<?php

namespace App\Actions\LoanRepayment;

use App\Models\LoanRepayment;
use Lorisleiva\Actions\Concerns\AsAction;

class CalculateInterest
{
    use AsAction;

    /**
     * Calculate interest amount and total amount based on global interest rates settings
     *
     * @param LoanRepayment $loanRepayment
     * @return LoanRepayment|null
     */
    public function handle(LoanRepayment $loanRepayment)
    {
        // Get interest rate and amount for calculations

        $interestRate = config('settings.interest_rate');
        $amount = $loanRepayment->amount;

        // Calculate interest amount

        $interestAmount = round( $amount * ($interestRate / 100), 2);

        // Calculate total amount

        $totalAmount = round($interestAmount + $amount, 2);

        // Update loan repayment model

        $loanRepayment->update([
            'interest' => $interestRate,
            'interest_amount' => $interestAmount,
            'total_amount' => $totalAmount,
        ]);

        return $loanRepayment->fresh();
    }
}
