<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): Model;

    public function update(array $data, int $id): ?int;

    public function delete(int $id);

    public function find(int $id): ?Model;
}