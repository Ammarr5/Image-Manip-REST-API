<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ResizeImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows("create");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules =  [
            "image" => ["required", "image"],
            "w" => ["required", "regex:/^\d+(\.\d+)?%?$/"],
            "h" => "regex:/^\d+(\.\d+)?%?$/",
        ];

        return $rules;
    }
}
