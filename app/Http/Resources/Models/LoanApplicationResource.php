<?php

namespace App\Http\Resources\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanApplicationResource extends JsonResource
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
            'amount' => '$' . $this->amount,
            'term' => $this->term,
            'term_period' => $this->term_period,
            'user' => new UserResource($this->whenLoaded('user')),
            'approved_by' => new UserResource($this->whenLoaded('approvedBy')),
        ];
    }
}
