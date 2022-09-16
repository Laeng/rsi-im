<?php

namespace App\Http\Controllers\User\Api;

use App\Models\Device;
use App\Models\User;
use App\Services\RSI\Interfaces\RsiServiceInterface;
use App\Services\User\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
     * [GET] /api/v1/user
     * user profile.
     *
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

        $data = $this->unset($data, config('services.rsi.attribute.00'));
        $data = $this->unset($data, config('services.rsi.attribute.01'));
        $data = $this->unset($data, config('services.rsi.attribute.02'));
        $data = $this->unset($data, config('services.rsi.attribute.03'));
        $data = $this->unset($data, config('services.rsi.attribute.04'));

        return $this->response(true, 'OK', 'Success', $data);
    }

    /**
     * [GET] /api/v1/user/organizations
     * User joined organizations.
     *
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
     * [GET] /api/v1/user/games
     * User owned games.
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
        $games = $this->rsiService->getGames($device);

        if (!key_exists('data', $games)) {
            return $this->response(false, $games['code'], $games['msg']);
        }

        $library = $this->rsiService->getLibrary($device, $games['data']);

        if (!key_exists('data', $library) || count($library['data']) == 0) {
            return $this->response(false, $library['code'], $library['msg']);
        }

        return $this->response(true, 'OK', 'Success', $library['data']);
    }

    /**
     * [GET] /api/v1/user/launch
     * User launch data.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function launch(Request $request): JsonResponse
    {
        $user = $request->user();

        if (is_null($user)) {
            return $this->response(false, 'ErrNotFoundUser', 'Can not found user');
        }

        $device = $this->rsiService->getDevice('user_id', $user->getAttribute('id'));

        if (!$request->has('game_id')) {
            return $this->response(false, 'ErrValidationFail', 'check game id');
        }

        if (!$request->has('channel_id')) {
            return $this->response(false, 'ErrValidationFail', 'check channel id');
        }

        $games = $this->rsiService->getGames($device);

        if (!key_exists('data', $games)) {
            return $this->response(false, $games['code'], $games['msg']);
        }

        $release = $this->rsiService->getRelease(
            $device,
            $games['data'],
            $request->get('game_id'),
            $request->get('channel_id')
        );

        if (!key_exists('data', $release)) {
            return $this->response(false, $release['code'], $release['msg']);
        }

        return $this->response(true, 'OK', 'Success', $release['data']);
    }

    /**
     * [GET] /api/v1/user/check
     * authentication status.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function check(Request $request): JsonResponse
    {
        $user = $request->user();

        if (is_null($user)) {
            return $this->response(false, 'ErrNotFoundUser', 'Can not found user');
        }

        $device = $this->rsiService->getDevice('user_id', $user->getAttribute('id'));
        $response = $this->rsiService->check($device);

        return $this->response(true, 'OK', 'Success', $response['data']);
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
