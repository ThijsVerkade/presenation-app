<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisplayRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'width' => 'required|string',
            'height' => 'required|string',
            'order' => 'sometimes|numeric|min:0',
        ];
    }
}
