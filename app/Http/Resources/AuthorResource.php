<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'name' => (string) $this->name,
            'active' => (int) $this->active,
            'createdAt' => (string) $this->created_at,
            'books' => $this->includeBooks(),
        ];
    }

    public function includeBooks(){
        return BookResource::collection($this->whenLoaded('books'));
    }
}