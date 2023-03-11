<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules()
    {
        return [
            'status' => 'required|integer',
            'data' => 'required|array',
            'data.event' => 'required|string|in:invoice-create,invoice-row-create,invoice-row-update,invoice-cancel',
            'data.payload' => 'required|array',
            'data.payload.customer' => 'required|array',
            'data.payload.customer.id' => 'required|string|uuid',
            'data.payload.customer.businessName' => 'required|string|max:255',
            'data.payload.customer.vat' => 'required|string|max:20',
            'data.payload.progressive' => 'required|string|max:255',
            'data.payload.total' => 'required|numeric|min:0',
            'data.payload.rows' => 'sometimes|array',
            'data.payload.rows.*.id' => 'required|string|uuid',
            'data.payload.rows.*.description' => 'required|string|max:255',
            'data.payload.rows.*.total' => 'required|numeric|min:0',
            'data.payload.rows.*.quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'data.event.in' => 'unknown event type',
        ];
    }

    public function failedValidation(Validator $validator)
    {   
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => $validator->errors(),
        ], Response::HTTP_BAD_REQUEST));
    }

}