<?php

namespace App\Libraries;

use App\Helpers\AppHelper;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Api {
    CONST CODE_SUCCESS = 1;
    CONST CODE_ALERT = 2;
    CONST CODE_ERROR = 3;
    CONST CODE_ERROR_DB = 4;

    CONST IDX_STR_API_NAME = 'jsonapi';
    CONST IDX_STR_API_VERSION = 'version';
    CONST IDX_STR_JSON_STATUS = "status";
    CONST IDX_STR_JSON_CODE = "code";
    CONST IDX_STR_JSON_MESSAGE = "message";
    CONST IDX_STR_JSON_MESSAGE_DETAIL = "description";
    CONST IDX_STR_JSON_ERRORS = "errors";
    CONST IDX_STR_JSON_DATA = "data";
    CONST IDX_STR_JSON_CACHE = "cache";

    protected $response = array(
        self::IDX_STR_API_NAME => array(
            self::IDX_STR_API_VERSION => '1.0.0'
        ),
        self::IDX_STR_JSON_STATUS => self::CODE_SUCCESS,
        self::IDX_STR_JSON_CODE => Response::HTTP_OK,
        self::IDX_STR_JSON_MESSAGE => 'OK',
    );

    /**
     * Estructura de respuesta JSON
     *
     * @param object|array|null $data
     * @param string $message
     * @param int $code
     * @param int $status
     * @param array $extras
     * @return array
     */
    public function makeResponse($data, string $message, int $status, int $code, array $extras = []): array {
        $response = $this->response;

        $response[self::IDX_STR_JSON_CODE] = $code;
        $response[self::IDX_STR_JSON_MESSAGE] = $message;

        if(!is_null($data)){
            $response[self::IDX_STR_JSON_DATA] = $data;
        }

        foreach ($extras as $key => $value){
            if(config('app.env') === AppHelper::ENVIRONMENT_PRODUCTION && $key === 'exception'){
                continue;
            }

            $response[$key] = $value;
        }

        return $response;
    }
}
