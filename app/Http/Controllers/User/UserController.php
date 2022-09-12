<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Device\Interfaces\DeviceRepositoryInterface;
use App\Services\RSI\Interfaces\RsiServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use phpseclib3\File\ASN1\Maps\Attribute;

class UserController extends Controller
{
    /**
     * [GET] user
     *
     * @link /my
     * @param Request $request
     * @return InertiaResponse
     */
    public function index(Request $request): InertiaResponse
    {


        return Inertia::render('user/index', []);
    }


    /**
     * [GET] user account
     *
     * @link /my/account
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
     * [GET] user log
     *
     * @param Request $request
     * @param RsiServiceInterface $rsiService
     * @return InertiaResponse
     * @link /my/log
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

}
