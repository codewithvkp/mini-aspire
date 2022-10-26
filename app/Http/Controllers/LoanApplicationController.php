<?php

namespace App\Http\Controllers;

use App\Events\LoanApplication\LoanApplicationApproved;
use App\Events\LoanApplication\LoanApplicationUpdated;
use App\Http\Requests\LoanApplication\ApproveLoanApplicationRequest;
use App\Http\Requests\LoanApplication\DeleteLoanApplicationRequest;
use App\Http\Requests\LoanApplication\StoreLoanApplicationRequest;
use App\Http\Requests\LoanApplication\UpdateLoanApplicationRequest;
use App\Http\Resources\General\SuccessResource;
use App\Http\Resources\Models\LoanApplicationResource;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LoanApplicationController extends Controller
{
    /**
     * LoanApplicationController's constructor
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only([
            'index',
            'store',
            'approve',
            'update',
            'destroy',
        ]);
    }

    /**
     * Get/filter list of loan applications
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $models = QueryBuilder::for(LoanApplication::class)
            ->allowedFilters([
                AllowedFilter::scope('approved'),
                AllowedFilter::scope('pending'),
            ]);

        // If auth user is admin, return all loan applications along with user and approvedBy relations

        if ($request->user()->isAdmin()) {
            $models = $models->with(['user', 'approvedBy']);
        }

        // If auth user is not admin, return only auth user's load applications

        if (!$request->user()->isAdmin()) {
            $models = $models->where('user_id', $request->user()->id);
        }

        $models = $models->get();

        return LoanApplicationResource::collection($models);
    }

    /**
     * Create a new loan application
     *
     * @param StoreLoanApplicationRequest $request
     * @return LoanApplicationResource
     */
    public function store(StoreLoanApplicationRequest $request)
    {
        $loanApplication = LoanApplication::query()->create($request->validated());
        return new LoanApplicationResource($loanApplication);
    }

    /**
     * Update existing loan application
     *
     * @param UpdateLoanApplicationRequest $request
     * @param LoanApplication $loanApplication
     * @return LoanApplicationResource
     */
    public function update(UpdateLoanApplicationRequest $request, LoanApplication $loanApplication)
    {
        if ($loanApplication->isApproved()) {
            abort(403, "Approved application can't be edited!");
        }

        $loanApplication->update($request->validated());

        event(new LoanApplicationUpdated($loanApplication));

        return new LoanApplicationResource($loanApplication->fresh());
    }

    /**
     * Delete existing loan application
     *
     * @param DeleteLoanApplicationRequest $request
     * @param LoanApplication $loanApplication
     * @return SuccessResource
     */
    public function destroy(DeleteLoanApplicationRequest $request, LoanApplication $loanApplication)
    {
        if ($loanApplication->isApproved()) {
            abort(403, "Approved application can't be deleted!");
        }

        $loanApplication->delete();
        return new SuccessResource([]);
    }

    /**
     * Approve pending loan application
     *
     * @param ApproveLoanApplicationRequest $request
     * @param LoanApplication $loanApplication
     * @return SuccessResource
     */
    public function approve(ApproveLoanApplicationRequest $request, LoanApplication $loanApplication)
    {
        if ($loanApplication->isApproved()) {
            abort(403, "This application has already been approved!");
        }

        $loanApplication->update([
            'approved_at' => now(),
            'approved_by' => $request->user()->id
        ]);

        event(new LoanApplicationApproved($loanApplication));

        return new SuccessResource([]);
    }
}
