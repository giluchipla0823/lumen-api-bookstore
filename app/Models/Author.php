<?php

namespace App\Models;

use App\Http\Resources\AuthorResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;

    protected $table = 'authors';

    public $transformer = AuthorResource::class;

    protected $fillable = array(
        'name'
    );

    public function books(){
        return $this->hasMany(Book::class);
    }
}
