<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Mengatur agar permintaan selalu diizinkan. Anda dapat mengubahnya sesuai kebutuhan.
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|string',
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
            'name.required' => 'Nama menu wajib diisi.',
            'name.string' => 'Nama menu harus berupa teks.',
            'name.max' => 'Nama menu tidak boleh lebih dari 255 karakter.',

            'description.required' => 'Deskripsi menu wajib diisi.',
            'description.string' => 'Deskripsi menu harus berupa teks.',

            'price.required' => 'Harga menu wajib diisi.',
            'price.numeric' => 'Harga menu harus berupa angka.',

            'image.required' => 'Gambar menu wajib diisi.',
            'image.string' => 'Gambar menu harus berupa URL atau string.',
        ];
    }
}
