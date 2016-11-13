<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ShortenUrlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url' => 'url',
            'urls' => 'array',
            'urls.desktop' => 'required_with:urls|url',
            'urls.mobile' => 'url',
            'urls.tablet' => 'url',
        ];
    }

    public function response(array $errors)
    {
        return response()->json([
            'code' => 422,
            'message' => 'Validation failed',
            'data' => ['errors' => $errors]
        ], 422);
    }
}
