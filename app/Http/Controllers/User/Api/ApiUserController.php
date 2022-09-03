<?php

namespace App\Http\Controllers\User\Api;

use App\Models\Device;
use App\Models\User;
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

    /**
     * [GET] user profile.
     *
     * @link /api/v1/user
     * @param Request $request
     * @return JsonResponse
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        if (is_null($user)) {
            return $this->response(false, 'ErrNotFoundUser', 'Can not found user');
        }

        $data = $user->getAttribute('data');

        $data = $this->unset($data, config('services.rsi.attribute-redacted.00'));
        $data = $this->unset($data, config('services.rsi.attribute-redacted.01'));
        $data = $this->unset($data, config('services.rsi.attribute-redacted.02'));
        $data = $this->unset($data, config('services.rsi.attribute-redacted.03'));
        $data = $this->unset($data, config('services.rsi.attribute-redacted.04'));

        return $this->response(true, 'OK', 'Success', $data);
    }

    /**
     * [GET] User joined organizations.
     *
     * @link /api/v1/user/organizations
     * @param Request $request
     * @return JsonResponse
     */
    public function organizations(Request $request): JsonResponse
    {
        $user = $request->user();

        if (is_null($user)) {
            return $this->response(false, 'ErrNotFoundUser', 'Can not found user');
        }

        $organizations = [];
        $data = $user->getAttribute('data');

        if (key_exists('organizations', $data)) {
            $organizations['organizations'] = $data['organizations'];
        }

        return $this->response(true, 'OK', 'Success', $organizations);
    }

    /**
     * [GET] User owned games.
     *
     * @link /api/v1/user/games
     * @param Request $request
     * @return JsonResponse
     */
    public function games(Request $request): JsonResponse
    {
        $user = $request->user();

        if (is_null($user)) {
            return $this->response(false, 'ErrNotFoundUser', 'Can not found user');
        }

        $device = $this->rsiService->getDevice('user_id', $user->getAttribute('id'));
        $response = $this->rsiService->getGames($device);

        return $this->response(false, 'Err', 'empty response');
    }

    /**
     * [GET] User launch data.
     *
     * @link /api/v1/user/launch
     * @param Request $request
     * @return JsonResponse
     */
    public function launch(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->response(false, 'Err', 'empty response');
    }

    private function response(bool $success, string $code, string $message, array $data = []): JsonResponse
    {
        return response()->json([
            'success' => intval($success),
            'code' => $code,
            'msg' => $message,
            'data' => $data
        ]);
    }

    private function unset(array $array, string $key): array
    {
        if (key_exists($key, $array)) {
            unset($array[$key]);
        }

        return $array;
    }
}
