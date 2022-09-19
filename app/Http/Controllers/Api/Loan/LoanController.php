<?php

namespace App\Http\Controllers\Api\Loan;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanRequest;
use App\Http\Resources\LoanCollection;
use App\Http\Resources\LoanResource;
use App\Models\Loan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the loans.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {

            return response()->success('User Loans', new LoanResource($request->user()->loan));

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Store a newly created loan in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(LoanRequest $request): JsonResponse
    {
        try {

            $userLoan = $request->user()->loan()->firstOrCreate([
                'amount' => $request->amount,
                'term' => $request->term,
            ]);

            return response()->success('Loan Created Successfully', new LoanResource($userLoan));

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified loan.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, Loan $loan): JsonResponse
    {
        try {

            if ($request->user()->can('view', $loan)) {
                return response()->success('User Loan Details', new LoanResource($request->user()->loan));
            } else {
                return response()->json(['status' => false, 'message' => 'Unauthorized Request'], 403);
            }

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Update the specified loan in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(LoanRequest $request, Loan $loan): JsonResponse
    {
        try {

            $loan->update($request->except('status'));
            return response()->success('Loan Details Updated Successfully.', new LoanResource($loan));

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
