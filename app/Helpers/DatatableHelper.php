<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class DatatableHelper
{
    public static function response(JsonResponse $json){
        $data = $json->getData();
        $data = (array) $data;
        $data['items'] = $data['data'];

        unset($data['data']);

        return $data;
    }
}