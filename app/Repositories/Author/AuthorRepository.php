<?php

namespace App\Repositories\Author;

use App\Helpers\AppHelper;
use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

class AuthorRepository implements AuthorRepositoryInterface {

    private $model;

    public function __construct(Author $author)
    {
        $this->model = $author;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function filter(Request $request): Collection
    {
        $query = $this->model;

        if($includes = AppHelper::getIncludesFromUrl()){
            $query = $query->with($includes);
        }

        if($request->has('name')){
            $query = $query->where('name', 'LIKE', "%" . $request->get('name') . "%");
        }

        return $query->get();
    }

    public function find(int $id): ?Model
    {
        // TODO: Implement find() method.
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }

    public function update(array $data, int $id): ?int
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function restore(int $id)
    {
        return $this->model->withTrashed()->findOrFail($id)->restore();
    }
}