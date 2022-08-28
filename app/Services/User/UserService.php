<?php

namespace App\Services\User;



use App\Models\User;
use App\Repositories\User\Interfaces\DeviceRepositoryInterface;
use App\Services\User\Interfaces\UserServiceInterface;

/**
 * Class AuthService
 * @package App\Services
 */
class UserService implements UserServiceInterface
{
    private DeviceRepositoryInterface $userRepository;

    public function __construct(DeviceRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store(int $accountId, array $data): User
    {
        $user = $this->userRepository->findById($accountId);

        if (is_null($user)) {
            $user = $this->userRepository->create([
                'id' => $accountId,
                'data' => $data
            ]);
        } else {
            $user->update([
                'data' => $data
            ]);
        }

        return $user;
    }
}
