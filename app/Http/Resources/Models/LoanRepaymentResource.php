<?php

namespace App\Http\Resources\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanRepaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'payment_date' => $this->payment_date,
            'week' => $this->week,
            'paid_at' => $this->paid_at,
            'amount' => '$' . $this->amount,
            'interest' => $this->interest . '%',
            'interest_amount' => '$' . $this->interest_amount,
            'total_amount' => '$' . $this->total_amount,
            'loan_application' => new LoanApplicationResource($this->whenLoaded('loanApplication')),
        ];
    }
}
