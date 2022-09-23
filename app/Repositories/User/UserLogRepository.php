<?php

namespace App\Repositories\User;

use App\Models\UserLog;
use App\Repositories\Base\BaseRepository;
use App\Repositories\User\Interfaces\UserLogRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use JetBrains\PhpStorm\Pure;

class UserLogRepository extends BaseRepository implements UserLogRepositoryInterface
{
    public UserLog $model;

    #[Pure]
    public function __construct(UserLog $model)
    {
        parent::__construct($model);

        $this->model = $model;
    }

    public function pagination(int $offset = 0, int $limit = 10, array $columns = ['*'], array $relations = []): ?Collection
    {
        return $this->model->select($columns)->with($relations)->offset($offset)->limit($limit)->latest()->get();
    }

    public function new(): UserLog
    {
        return new UserLog();
    }

    public function paginateByUserId(int $userId, int $perPages = 15, array $columns = ['*'], string $pageName = 'pages'): Paginator
    {
        return $this->model->where('user_id', $userId)->latest()->paginate($perPages, $columns, $pageName);
    }
}
