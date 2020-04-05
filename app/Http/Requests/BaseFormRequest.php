<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Validation\ValidationException;
use Urameshibr\Requests\FormRequest;

class BaseFormRequest extends FormRequest
{
    use ApiResponse;

    /**
     * @param array $errors
     * @return void
     * @throws ValidationException
     */
    public function response(array $errors)
    {
        throw new ValidationException($this->getValidatorInstance());
    }
}
