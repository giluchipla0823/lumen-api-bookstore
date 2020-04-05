<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'authorId' => !is_null($this->author_id) ? (int) $this->author_id : NULL,
            'publisherId' => !is_null($this->publisher_id) ? (int) $this->publisher_id : NULL,
            'title' => (string) $this->title,
            'summary' => (string) $this->summary,
            'description' => (string) $this->description,
            'quantity' => (int) $this->quantity,
            'price' => (string) $this->price,
            'image' => (string) $this->image,
            'createdAt' => (string) $this->created_at,
            'author' => $this->includeAuthor()
        ];
    }

    public function includeAuthor(){
        return new AuthorResource($this->whenLoaded('author'));
    }
}