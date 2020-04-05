<?php

namespace App\Traits;

use App\Helpers\AppHelper;
use App\Helpers\DatatableHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;

Trait ResponseTransformer
{

    /**
     * Transformar instancias de un modelo
     *
     * @param Model $instance
     * @return array|Model|Collection
     */
    protected function transformInstance(Model $instance){
        $this->setRelationships($instance);

        return $this->transformData($instance, $instance->transformer);
    }

    /**
     * Transformar colecciones
     *
     * @param Collection $collection
     * @return array|Model|Collection
     */
    protected function transformCollection(Collection $collection){
        $this->setRelationships($collection->first());

        return $this->transformData($collection, $collection->first()->transformer);
    }

    /**
     * Cargar relaciones para instancia según el parámetro "includes"
     * enviado en el queryParams
     *
     * @param Model $instance
     * @return Model
     */
    protected function setRelationships(Model $instance){
        return $instance->load(AppHelper::getIncludesFromUrl());
    }

    /**
     * Resuelve el recurso a transformar y devuelve un array
     *
     * @param Collection|Model $data
     * @param string|null $transformer
     * @return array|Collection|Model
     */
    private function transformData($data, ?string $transformer) {
        if(!class_exists($transformer)){
            return $data;
        }

        $resource = NULL;

        if($data instanceof Model){
            $resource = new $transformer($data);
        }else if($data instanceof Collection){
            $resource = $transformer::collection($data);
        }

        if(!$resource instanceof JsonResource){
            return [];
        }

        return $resource->response()->getData(TRUE)['data'];
    }

    /**
     * Transformar respuesta datatables
     *
     * @param Collection $collection
     * @return array|mixed
     * @throws \Exception
     */
    protected function transformDatatable(Collection $collection){
        $transformer = NULL;

        if (!$collection->isEmpty()) {
            $transformer = $collection->first()->transformer;
        }

        $collection = Datatables::of($collection);

        $self = $this;

        if($transformer){
            $collection->setTransformer(function(Model $item) use($self){
                return $self->transformData($item, $item->transformer);
            });
        }

        $collection = $collection->make(true);

        return DatatableHelper::response($collection);
    }
}