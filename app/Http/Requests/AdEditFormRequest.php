<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdEditFormRequest extends FormRequest
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

    public function rules()
    {
        return [
            'title' => 'required',
            'content'=> 'required',
            'categories' => 'required',
        ];
    }
}
