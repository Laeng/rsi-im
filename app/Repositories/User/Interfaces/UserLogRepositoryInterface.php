<?php

namespace App\Repositories\User\Interfaces;

use App\Models\User;
use App\Models\UserLog;
use App\Repositories\Base\Interfaces\EloquentRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

interface UserLogRepositoryInterface extends EloquentRepositoryInterface
{
    public function pagination(int $offset = 0, int $limit = 10, array $columns = ['*'], array $relations = []): ?Collection;

    public function paginateByUserId(int $userId, int $perPages = 15, array $columns = ['*'], string $pageName = 'pages'): Paginator;

    public function new(): UserLog;
}
