<?php

namespace App\Helpers;

class ValidationHelper
{

    /**
     * Aplicar formato a los errores de validación
     *
     * @param array $errors
     * @return array
     */
    public static function formatErrors(array $errors): array {
        $response = [];

        foreach ($errors as $key => $value) {
            $response[] = [
                'field' => $key
                , 'message' => $value[0]
            ];
        }

        return $response;
    }
}