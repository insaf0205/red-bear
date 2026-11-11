<?php

namespace App\Http\Requests;

use App\Models\Training;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreTrainingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'trainer_id'  => ['required', Rule::exists('users', 'id')->where('role', 'admin')],
            'start_at'    => ['required', 'date', 'after:now'],
            'end_at'      => ['required', 'date', 'after:start_at'],
            'name'        => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'cost' => "nullable|integer|min:400",
            'image' => "nullable|image|max:10240"
        ];
    }
}
