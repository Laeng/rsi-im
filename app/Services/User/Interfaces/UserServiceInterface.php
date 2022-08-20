<?php

namespace App\Services\User\Interfaces;


use App\Models\User;

/**
 * Interface AuthServiceContract
 * @package App\Services\Contracts
 */
interface UserServiceInterface
{
    public function store(int $accountId, array $data): User;
}
