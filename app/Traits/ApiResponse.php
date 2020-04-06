<?php

namespace App\Traits;

use App\Helpers\AppHelper;
use App\Libraries\Api;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

Trait ApiResponse{

    /**
     * Crear respuesta de éxito
     *
     * @param $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data, ?string $message = NULL, int $code = Response::HTTP_OK){
        if(!$message){
            $message = Response::$statusTexts[$code];
        }

        return $this->makeResponse($data, $message, $code, Api::CODE_SUCCESS);
    }

    /**
     * Crear respuesta para colleciones de datos
     *
     * @param Collection $collection
     * @return JsonResponse
     */
    protected function showAll(Collection $collection){
        if(
            AppHelper::isUsingDatatableList() &&
            method_exists($this,'transformDatatable')
        ){
            $collection = $this->transformDatatable($collection);
        }else if (method_exists($this,'transformCollection')){
            $collection = $this->transformCollection($collection);
        }

        return $this->successResponse($collection);
    }

    /**
     * Crear respuesta para instancias de modelos de eloquent
     *
     * @param Model $instance
     * @return JsonResponse
     */
    protected function showOne(Model $instance){
        if (method_exists($this,'transformInstance')){
            $instance = $this->transformInstance($instance);
        }

        return $this->successResponse($instance);
    }

    /**
     * Crear respuesta para mostrar mensajes
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function showMessage(string $message, int $code = Response::HTTP_OK){
        return $this->successResponse(NULL, $message, $code);
    }

    /**
     * Crear respuesta de error
     *
     * @param string $message
     * @param int $code
     * @param int $status
     * @param array $extras
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $code, int $status = Api::CODE_ERROR, array $extras = []){
        return $this->makeResponse(NULL, $message, $code, $status, $extras);
    }

    /**
     * Estructura de respuesta JSON
     *
     * @param object|array|null $data
     * @param string $message
     * @param int $code
     * @param int $status
     * @param array $extras
     * @return JsonResponse
     */
    protected function makeResponse($data, string $message, int $code, int $status, array $extras = []) {
        if(property_exists($this, 'dataException')){
            $extras = array_merge($extras, ['exception' => $this->dataException]);
        }

        $response = (new Api)->makeResponse(
            $data,
            $message,
            $status,
            $code,
            $extras
        );

        return response()->json($response, $code);
    }
}
