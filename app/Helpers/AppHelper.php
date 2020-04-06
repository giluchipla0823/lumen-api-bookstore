<?php

namespace App\Helpers;

class AppHelper
{
    CONST ENVIRONMENT_PRODUCTION = 'production';
    CONST REQUEST_KEY_DATATABLE = 'listFormat';
    CONST REQUEST_VALUE_DATATABLE = 'datatables';

    /**
     * Obtener las relaciones de modelos del queryParams "includes"
     *
     * @param array $default
     * @return array
     */
    public static function getIncludesFromUrl($default = []){
        $includes = request()->get('includes');

        if(!$includes){
            return $default;
        }

        return explode(',', $includes);
    }

    /**
     * Determina si el formato de lista solicitado es "datatables"
     *
     * @return bool
     */
    public static function isUsingDatatableList(){
        return request()->get(self::REQUEST_KEY_DATATABLE) === self::REQUEST_VALUE_DATATABLE;
    }
}