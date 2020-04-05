<?php

namespace App\Models;

use App\Http\Resources\BookResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $table = 'books';

    public $transformer = BookResource::class;

    protected $fillable = array(
        'author_id',
        'publisher_id',
        'title',
        'summary',
        'description',
        'quantity',
        'price'
    );

    public function author(){
        return $this->belongsTo(Author::class);
    }

}
