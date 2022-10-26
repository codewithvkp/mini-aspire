<?php

namespace App\Http\Requests\LoanApplication;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLoanApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (int) $this->route('loan_application')->user_id === (int) $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => [
                'numeric',
            ],
            'term' => [
                'integer',
                'min:1',
                'max:100',
            ],
            'term_period' => [
                Rule::in(['mo'])
            ]
        ];
    }
}
