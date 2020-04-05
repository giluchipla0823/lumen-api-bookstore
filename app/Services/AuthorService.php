<?php

namespace App\Services;

use App\Repositories\Author\AuthorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuthorService
{
    private $repository;

    public function __construct(AuthorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all(): Collection{
        return $this->repository->all();
    }

    public function filter(Request $request): Collection{
        return $this->repository->filter($request);
    }

    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    public function update(array $data, int $id): ?int
    {
        return $this->repository->update($data, $id);
    }

    public function restore(int $id)
    {
        return $this->repository->restore($id);
    }
}