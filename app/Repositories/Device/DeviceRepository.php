<?php

namespace App\Repositories\Device;

use App\Models\User;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Device\Interfaces\DeviceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use JetBrains\PhpStorm\Pure;

class DeviceRepository extends BaseRepository implements DeviceRepositoryInterface
{
    public User $model;

    #[Pure]
    public function __construct(User $model)
    {
        parent::__construct($model);

        $this->model = $model;
    }

    public function findByIds(array $ids, array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->select($columns)->whereIn('id', $ids)->with($relations)->latest()->get();
    }

    public function pagination(int $offset = 0, int $limit = 10, array $columns = ['*'], array $relations = []): ?Collection
    {
        return $this->model->select($columns)->with($relations)->offset($offset)->limit($limit)->latest()->get();
    }

    public function new(): User
    {
        return new User();
    }
}
