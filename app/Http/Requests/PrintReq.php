<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class PrintReq extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.*.products.*.quantity' => 'required|numeric',
            'data.*.products.*.name' => 'required|string',
            'data.*.products.*.note' => 'nullable',
            'data.*.products' => 'required|array',
            'data.*.customer' => 'required|string',
            'data.*.code' => 'required|string',
            'data.*.date' => 'required|string',
            'data.*.name' => 'required|string',
            'data.*.cut' => 'required|boolean',
            'data.*.ip' => 'required|string',
            'data.*.tables' => 'nullable|array',
            'data' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'data.*.name.required' => 'Dapur tidak boleh kosong.',
            'data.*.file.required' => 'File tidak boleh kosong.',
            'data.*.cut.required' => 'Potongan tidak boleh kosong.',
            'data.*.ip.required' => 'IP tidak boleh kosong.',
            'data.*.name.string' => 'Dapur harus berupa string.',
            'data.*.file.string' => 'File harus berupa string.',
            'data.*.cut.boolean' => 'Potongan harus boolean.',
            'data.*.ip.string' => 'IP harus berupa string.',
            'data.array' => 'Data harus berupa array.',
            'data.required' => 'Data tidak boleh kosong.',
        ];
    }

    public function failedValidation(Validator $validator): never
    {
        $errors = [];

        foreach ($validator->errors()->toArray() as $index => $value) {
            $errors[] = [
                'property' => $index,
                'message' => $value[0],
            ];
        }

        throw new HttpResponseException(response()->json($errors, 404));
    }
}
