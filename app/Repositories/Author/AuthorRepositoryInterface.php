<?php

namespace App\Repositories\Author;

use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Request;

interface AuthorRepositoryInterface extends RepositoryInterface
{
    public function restore(int $id);

    public function filter(Request $request): Collection;
}