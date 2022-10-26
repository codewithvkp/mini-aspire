<?php

namespace App\Http\Requests\LoanRepayment;

use Illuminate\Foundation\Http\FormRequest;

class RepayLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (int) $this->route('loan_repayment')->loanApplication->user_id === (int) $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
