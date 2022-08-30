<?php

namespace App\Repositories\Device\Interfaces;

use App\Models\Device;
use App\Repositories\Base\Interfaces\EloquentRepositoryInterface;


interface DeviceRepositoryInterface extends EloquentRepositoryInterface
{
    public function findByUserId(string $userId, array $columns = ['*'], array $relations = []): ?Device;

    public function findByHash(string $hash, array $columns = ['*'], array $relations = []): ?Device;
}
