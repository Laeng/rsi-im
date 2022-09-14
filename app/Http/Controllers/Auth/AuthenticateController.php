<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Services\RSI\Interfaces\RsiServiceInterface;
use App\Services\User\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * [GET] Front of sign-in page.
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
     * [GET] Sign-out page.
     *
     * @link /disconnect
     * @param Request $request
     * @return RedirectResponse
     */
    public function signOut(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerate();
        }

        return redirect()->route('welcome');
    }

    /**
     * [POST] Check captcha code
     *
     * @link /connect/captcha
     * @param Request $request
     * @return JsonResponse
     */
    public function captcha(Request $request): JsonResponse
    {
        $device = $this->getDevice($request);

        if (is_null($device)) {
            $captchaData = [
                'success' => 0,
                'code' => 'ErrSessionExpired',
                'message' => '',
                'data' => [
                    'image' => null
                ]
            ];

        } else {
            $captchaData = $this->rsiService->getCaptcha($device);
        }

        return response()->json($captchaData);
    }

    /**
     * [POST] Check multi factor authorize code.
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

        $device = $this->getDevice($request);

        if (is_null($device)) {
            $receiveData = [
                'success' => 0,
                'code' => 'ErrSessionExpired',
                'message' => '',
                'data' => []
            ];

        } else {
            $code = $request->get('code');
            $duration = $request->get('duration');

            $receiveData = $this->rsiService->verifyMultiFactor(
                $device,
                $code,
                $duration
            );

            $request->session()->put('duration', $duration);
        }

        return $this->response($request, $receiveData);
    }

    /**
     * [POST] Process sing-in
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

        if (is_null($device)) {
            $receiveData = [
                'success' => 0,
                'code' => 'ErrNotFoundDeviceData',
                'message' => 'Not found device date'
            ];
        } else {
            $session = $request->session();
            $session->put('username', $username);
            $session->put('device_id', $device->getAttribute('id'));

            $receiveData = $this->rsiService->signIn(
                $device,
                $username,
                $password,
                $captcha
            );
        }

        return $this->response($request, $receiveData);
    }

    /**
     * Get Device Model
     *
     * @param Request $request
     * @return Device|null
     */
    private function getDevice(Request $request): ?Device
    {
        $session = $request->session();

        if ($session->has('device_id')) {
            $deviceId = $session->get('device_id');

            return $this->rsiService->getDevice('id', $deviceId);
        }

        return null;
    }

    /**
     * Handling response to request.
     *
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
                $session = $request->session();
                $device = $this->getDevice($request);

                if (is_null($device)) {
                    $data = array_merge($data, [
                        'avatar' => null,
                        'organizations' => []
                    ]);

                } else {
                    if ($session->has('username')) {
                        $data = array_merge(['username' => $session->pull('username')], $data);
                    }

                    $spectrum = $this->rsiService->getSpectrum($device);

                    if (key_exists('data', $spectrum)) {
                        $data = array_merge($data, $spectrum['data']);
                    }
                }

                $user = $this->userService->store($data['account_id'], $data);
                $userId = $user->getAttribute('id');

                if (is_null($device->getAttribute('user_id'))) {
                    $deviceData = [
                        'user_id' => $userId,
                        'duration' => $session->has('duration') ? $session->pull('duration') : 'session',
                        'ip' => $request->getClientIp()
                    ];

                    $this->rsiService->setDevice('id', $device->getAttribute('id'), $deviceData);
                }

                Auth::loginUsingId($userId);
                $session->regenerate();
            }
        }

        return match ($receiveData['code']) {
            "OK" => redirect()->intended('/my'),
            default => redirect()->route('connect.sign-in')->with('data', [
                'code' => $receiveData['code'],
                'message' => $receiveData['msg']
            ]),
        };
    }
}
