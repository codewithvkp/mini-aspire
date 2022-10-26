<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanRepayment\RepayLoanRequest;
use App\Http\Resources\General\SuccessResource;
use App\Http\Resources\Models\LoanRepaymentResource;
use App\Models\LoanApplication;
use App\Models\LoanRepayment;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LoanRepaymentController extends Controller
{
    /**
     * LoanRepaymentController's constructor
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only([
            'index',
            'repay',
        ]);
    }

    /**
     * Get/filter repayment records for a particular loan application
     *
     * @param Request $request
     * @param LoanApplication $loanApplication
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, LoanApplication $loanApplication)
    {
        $models = QueryBuilder::for(LoanRepayment::class)
            ->allowedFilters([
                AllowedFilter::scope('paid'),
                AllowedFilter::scope('pending'),
            ])
            ->where('loan_application_id', $loanApplication->id)
            ->orderBy('week')
            ->get();

        return LoanRepaymentResource::collection($models);
    }

    /**
     * Repay loan for a particular repayment record
     *
     * @param RepayLoanRequest $request
     * @param LoanRepayment $loanRepayment
     * @return SuccessResource
     */
    public function repay(RepayLoanRequest $request, LoanRepayment $loanRepayment)
    {
        if ($loanRepayment->isPaid()) {
            abort(403, "Already paid!");
        }

        $loanRepayment->update([
            'paid_at' => now(),
        ]);

        return new SuccessResource([]);
    }
}
