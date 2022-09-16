<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RSI\Interfaces\RsiServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class UserController extends Controller
{
    /**
     * [GET] /my
     *
     * @param Request $request
     * @return InertiaResponse
     */
    public function index(Request $request): InertiaResponse
    {
        return Inertia::render('user/index', []);
    }


    /**
     * [GET] /my/account
     *
     * @param Request $request
     * @return InertiaResponse
     */
    public function account(Request $request): InertiaResponse
    {
        $user = Auth::user();
        $userData = $user->attributesToArray();

        unset($userData['id']);
        unset($userData['account_id']);
        unset($userData['remember_token']);

        return Inertia::render('user/account', [
            'data' => $user->attributesToArray()
        ]);
    }

    /**
     * [GET] /my/log
     *
     * @param Request $request
     * @param RsiServiceInterface $rsiService
     * @return InertiaResponse
     */
    public function log(Request $request, RsiServiceInterface $rsiService): InertiaResponse
    {
        $user = Auth::user();
        $device = $rsiService->getDevice('user_id', $user->getAttribute('id'));
        $deviceData = $device->attributesToArray();

        unset($deviceData['hash']);
        unset($deviceData['data']);

        return Inertia::render('user/device', [
            'data' => $deviceData
        ]);
    }

    /**
     * [DELETE] /my/data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $user->device()->delete();
        $user->token()->delete();
        $user->log()->delete();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerate();

        return response()->json([
            // EMPTY
        ]);
    }

}
