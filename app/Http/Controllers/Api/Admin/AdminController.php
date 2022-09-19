<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanApproveRequest;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Update the Loan Status by Admin
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(LoanApproveRequest $request, $id): JsonResponse
    {
        try {

            $loan = Loan::find($id);

            if (!$loan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Not Found',
                ], 404);
            }

            $data = $request->all();
            $loan->update($data);

            $amount = $loan->amount;
            $term = $loan->term;
            $date = $loan->created_at->format('Y-m-d');

            // IF Admin Approve the Loan
            if ($loan->status) {

                if ($loan->payments->count() == $term) {
                    return response()->success('Loan Approved by Admin',$loan);
                }

                $installment_amount = $amount/$term;

                $paymentData = [];
                for ($x = 1; $x <= $term; $x++) {

                    $rawDate = strtotime($date);
                    $dates = date('Y-m-d', $rawDate);

                    $rawDate = strtotime($date . ' +1 week');
                    $date = date('Y-m-d', $rawDate);

                    $paymentData[] = [
                        'loan_id' => $loan->id,
                        'status' => Loan::PENDING,
                        'amount' => $installment_amount,
                        'due_date' => $dates,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                }

                Payment::insert($paymentData);
            }

            $message = $loan->status == 1 ? 'Loan Approved' : 'Loan Rejected';

            return response()->success($message . ' by Admin','');

        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => $th->getMessage()], 500);
        }
    }
}
