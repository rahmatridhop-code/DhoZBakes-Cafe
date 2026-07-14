<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|integer|min:0',
            'emoji' => 'nullable|string|max:10',
            'badge' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ];
    }
}
