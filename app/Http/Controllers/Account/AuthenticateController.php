<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Services\RSI\Interfaces\RsiServiceInterface;
use App\Services\User\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class AuthenticateController extends Controller
{
    private RsiServiceInterface $rsiService;
    private UserServiceInterface $userService;

    public function __construct(RsiServiceInterface $rsiService, UserServiceInterface $userService)
    {
        $this->rsiService = $rsiService;
        $this->userService = $userService;
    }

    /**
     * [GET] front of sign-in page.
     *
     * @link /connect
     * @param Request $request
     * @return InertiaResponse
     */
    public function signIn(Request $request): InertiaResponse
    {
        $initData = ['code' => 'OK', 'message' => ''];
        $passData = $request->session()->get('data', $initData);

        return match ($passData['code']) {
            'ErrMultiStepRequired',
            'ErrMultiStepExpired',
            'ErrMultiStepWrongCode' => Inertia::render('auth/multi-factor', $passData),
            default => Inertia::render('auth/sign-in', $passData),
        };
    }

    /**
     * [POST] check captcha code
     *
     * @link /connect/captcha
     * @param Request $request
     * @return JsonResponse
     */
    public function captcha(Request $request): JsonResponse
    {
        $session = $request->session();

        if ($session->has('device_id')) {
            $deviceId = $session->get('device_id');
            $device = $this->rsiService->getDevice('id', $deviceId);
            $captchaData = $this->rsiService->getCaptcha($device);

        } else {
            $captchaData = [
                'success' => 0,
                'code' => 'ErrSessionExpired',
                'message' => '',
                'data' => [
                    'image' => null
                ]
            ];
        }

        return response()->json($captchaData);
    }

    /**
     * [POST] check multi factor authorize code.
     *
     * @link /connect/multi-factor
     * @param Request $request
     * @return RedirectResponse
     */
    public function multiFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
            'duration' => ['required', 'string'],
        ]);

        $session = $request->session();

        if ($session->has('device_id')) {
            $deviceId = $session->get('device_id');
            $device = $this->rsiService->getDevice('id', $deviceId);
            $receiveData = $this->rsiService->verifyMultiFactor(
                $device,
                $request->get('code'),
                $request->get('duration')
            );

        } else {
            $receiveData = [
                'success' => 0,
                'code' => 'ErrSessionExpired',
                'message' => '',
                'data' => []
            ];
        }

        return $this->response($request, $receiveData);
    }

    /**
     * [POST] process sing-in
     *
     * @link /connect/process
     * @param Request $request
     * @return RedirectResponse
     */
    public function process(Request $request):RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha' => ['string', 'nullable'],
        ]);

        $username = $request->get('username');
        $password = $request->get('password');
        $captcha = $request->get('captcha') ?? ''; // don't use default attribute

        $deviceHash = $this->rsiService->createDeviceHash($request->getClientIp(), $username);
        $device = $this->rsiService->getDevice('hash', $deviceHash);

        $request->session()->put('device_id', $device->getAttribute('id'));

        $receiveData = $this->rsiService->signIn(
            $device,
            $username,
            $password,
            $captcha
        );

        return $this->response($request, $receiveData);
    }

    /**
     * @param Request $request
     * @param array $receiveData
     * @return RedirectResponse
     */
    private function response(Request $request, array $receiveData): RedirectResponse
    {
        if (empty($receiveData)) {
            return redirect()->route('sign-in');
        }

        if ($receiveData['code'] === 'OK') {
            $data = key_exists('data', $receiveData) ? $receiveData['data'] : [];

            if (key_exists('account_id', $data)) {
                $this->userService->store($data['account_id'], $data);
            }
        }

        return match ($receiveData['code']) {
            "OK" => redirect()->intended('user.index'),
            default => redirect()->route('login')->with('data', [
                'code' => $receiveData['code'],
                'message' => $receiveData['msg']
            ]),
        };
    }

}
