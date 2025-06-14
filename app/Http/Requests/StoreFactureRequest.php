<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFactureRequest extends FormRequest
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
            'articles' => ['required', 'array', 'min:1'],
            'articles.*.article_id' => ['required', 'exists:articles,id'],
            'articles.*.quantity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:payé,impayé'],
        ];
    }
    public function messages(): array
    {
        return [
            'article_id.required' => 'Veuillez sélectionner un article.',
            'article_id.exists'   => 'Cet article n’existe pas.',
            'quantity.min'        => 'La quantité doit être au moins 1.',
            'status.in'           => 'Statut de paiement invalide.',
        ];
    }
}
