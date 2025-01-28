<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class PrintTableCheckerReq extends FormRequest
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
            'ip' => 'required|string',
            'code' => 'required|string',
            'customer' => 'required|string',
            'created_at' => 'required|string',
            'products' => 'required|array',
            'products.*.name' => 'required|string',
            'products.*.quantity' => 'required|numeric',
            'products.*.note' => 'nullable',
            'packets' => 'required|array',
            'packets.*.name' => 'required|string',
            'packets.*.quantity' => 'required|numeric',
            'packets.*.note' => 'nullable',
            'tables' => 'nullable|array',
        ];
    }

    public function attributes(){
        return [
            'ip' => 'Alamat IP',
            'code' => 'Nomor Invoice',
            'customer' => 'Nama Pembeli',
            'created_at' => 'Tanggal',
            'products' => 'Produk',
            'products.*.name' => 'Nama Produk',
            'products.*.quantity' => 'Jumlah Produk',
            'products.*.note' => 'Catatan Produk',
            'packets' => 'Paket',
            'packets.*.name' => 'Nama Paket',
            'packets.*.quantity' => 'Jumlah Paket',
            'packets.*.note' => 'Catatan Paket',
            'tables' => 'Nomor Meja',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'numeric' => ':attribute harus berupa angka',
            'array' => ':attribute harus berupa array',
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
