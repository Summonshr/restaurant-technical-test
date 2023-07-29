<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDishRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:dishes,name,'.$this->route('id'),
            'description' => 'required|unique:dishes,description,'.$this->route('id'),
            'image' => 'nullable|url',
            'price' => 'required|numeric|min:0',
        ];
    }
}
