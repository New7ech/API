<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Or based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255', // 'sometimes' for PATCH, 'required' for PUT
            'description' => 'nullable|string',
            'prix' => 'sometimes|required|numeric|min:0',
            'quantite' => 'sometimes|required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',    // Changed to nullable
            'fournisseur_id' => 'nullable|exists:fournisseurs,id', // Changed to nullable
            'emplacement_id' => 'nullable|exists:emplacements,id', // Changed to nullable
        ];
    }
}
