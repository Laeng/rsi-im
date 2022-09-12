<?php

namespace App\Repositories\Device\Interfaces;

use App\Models\Device;
use App\Repositories\Base\Interfaces\EloquentRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;


interface DeviceRepositoryInterface extends EloquentRepositoryInterface
{
    public function findByUserId(string $userId, array $columns = ['*'], array $relations = []): ?Device;

    public function findByHash(string $hash, array $columns = ['*'], array $relations = []): ?Device;

    public function paginateByUserId(int $userId, int $perPages = 15, array $columns = ['*'], string $pageName = 'pages'): Paginator;
}
