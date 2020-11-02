<?php

namespace App\Http\Requests\Author;

use App\Http\Requests\BaseFormRequest;

class UpdateAuthorRequest extends BaseFormRequest
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
        $id = $this->segment(4);

        return [
            'name' => "required|max:150|unique:authors,name,{$id},id"
        ];
    }
}
