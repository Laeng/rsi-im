<?php

namespace App\Repositories\Device;

use App\Models\Device;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Device\Interfaces\DeviceRepositoryInterface;
use JetBrains\PhpStorm\Pure;

class DeviceRepository extends BaseRepository implements DeviceRepositoryInterface
{
    public Device $model;

    #[Pure]
    public function __construct(Device $model)
    {
        parent::__construct($model);

        $this->model = $model;
    }

    public function findByHash(string $hash, array $columns = ['*'], array $relations = []): ?Device
    {
        return $this->model->select($columns)->where('hash', $hash)->with($relations)->latest()->first();
    }

    public function findByUserId(string $userId, array $columns = ['*'], array $relations = []): ?Device
    {
        return $this->model->select($columns)->where('user_id', $userId)->with($relations)->latest()->first();
    }
}
