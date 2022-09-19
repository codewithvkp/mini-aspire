<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {

            $payments = $request->user()->loan->payments;

            if (!$payments) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Not Found',
                ], 404);
            }

            return response()->success('Payments List', $payments);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(Payment $payment)
    {
        try {

            if (!$payment) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Not Found',
                ], 404);
            }

            return response()->success('Payment Data',$payment);

        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, Payment $payment): JsonResponse
    {
        try {

            $user = $request->user();

            $pending_payment = $user->loan->payments->where('status', Payment::PENDING)->count();

            $data = $request->all();
            $amount = $request->amount;

            if ($amount >= $payment->amount) {

                $data['amount'] = $amount;
                $data['status'] = $request->status;

                $payment->update($data);

                if (!$pending_payment) {
                    $loan = $user->loan;
                    $loan->status = Loan::PAID;
                    $loan->save();
                }

                return response()->success('Payments Data Updated Successfully',$payment);
            }

            return response()->json([
                'status' => true,
                'message' => 'Please Enter the Amount Correctly',
            ], 200);


        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => $th->getMessage()], 500);
        }
    }
}
