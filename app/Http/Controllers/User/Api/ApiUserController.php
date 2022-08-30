<?php

namespace App\Http\Controllers\User\Api;

use App\Services\RSI\Interfaces\RsiServiceInterface;
use App\Services\User\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiUserController
{
    private RsiServiceInterface $rsiService;
    private UserServiceInterface $userService;

    public function __construct(RsiServiceInterface $rsiService, UserServiceInterface $userService)
    {
        $this->rsiService = $rsiService;
        $this->userService = $userService;
    }

    public function index(Request $request): JsonResponse
    {

    }

    public function organizations(Request $request): JsonResponse
    {

    }

    public function games(Request $request): JsonResponse
    {

    }

    public function launch(Request $request): JsonResponse
    {

    }
}
