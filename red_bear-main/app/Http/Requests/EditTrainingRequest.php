<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditTrainingRequest extends FormRequest
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
            'start_at'    => ['nullable', 'date', 'after:now'],
            'end_at'      => ['nullable', 'date', 'after:start_at'],
            'name'        => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'image' => "nullable|image|max:10240",
            "cost" => "nullable|integer|min:400"
        ];
    }
}
