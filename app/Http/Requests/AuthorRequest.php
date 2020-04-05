<?php

namespace App\Http\Requests;

class AuthorRequest extends BaseFormRequest
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
        if($this->method() === 'GET'){
            return [
                'includes' => 'without_spaces|exists_relations:books'
            ];
        }

        if(in_array($this->method(), ['POST', 'PUT', 'PATCH'])){
            return $this->_rules();
        }
    }

    private function _rules(){
        $id = $this->segment(4);



        return [
            'name' => 'required|max:150|' . $this->_uniqueRule($id)
        ];
    }

    private function _uniqueRule($id){
        $rule = 'unique:authors,name';

        if($id){
            $rule .= ',' . $id . ',id';
        }

        return $rule;
    }


}
