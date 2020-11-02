<?php

namespace App\Http\Requests\Author;

use App\Http\Requests\BaseFormRequest;

class ListAuthorRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'includes' => 'without_spaces|exists_relations:books'
        ];
    }
}
