<?php

namespace App\Repositories\User\Interfaces;

use App\Models\User;
use App\Repositories\Base\Interfaces\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    public function findByIds(array $ids, array $columns = ['*'], array $relations = []): Collection;

    public function findByAccountId(string $accountId, array $columns = ['*'], array $relations = []): ?user;

    public function new(): User;
}
