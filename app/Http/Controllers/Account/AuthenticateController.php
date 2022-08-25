<?php

namespace App\Http\Controllers\Account;

use App\Models\User;
use App\Services\RSI\Interfaces\RsiServiceInterface;
use App\Services\User\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class AuthenticateController
{
    private RsiServiceInterface $rsiService;
    private UserServiceInterface $userService;

    public function __construct(RsiServiceInterface $rsiService, UserServiceInterface $userService)
    {
        $this->rsiService = $rsiService;
        $this->userService = $userService;

    }

    public function login(Request $request)
    {
        $data = ['code' => 'OK', 'message' => ''];
        $redirectData = $request->session()->get('data');

        if (!is_null($redirectData)) {
            $data = $redirectData;
        }

        return match ($data['code']) {
            'ErrMultiStepRequired',
            'ErrMultiStepExpired',
            'ErrMultiStepWrongCode' => Inertia::render('auth/multi-factor', $data),
            default => Inertia::render('auth/basic', $data),
        };
    }

    public function loginSubmit(Request $request): Response|RedirectResponse|InertiaResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha' => ['string', 'nullable'],
        ]);

        $loginData = $this->rsiService->login(
            $request->get('username'),
            $request->get('password'),
            $request->get('captcha') ?? ''
        );

        return $this->authSteps($request, $loginData);
    }

    public function loginCaptcha(Request $request): JsonResponse
    {
        $captchaData = $this->rsiService->captcha();

        return response()->json($captchaData);
    }

    public function loginMultiFactor(Request $request): Response|RedirectResponse|InertiaResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
            'duration' => ['required', 'string'],
        ]);

        $loginData = $this->rsiService->multiFactor(
            $request->get('code'),
            $request->get('duration')
        );

        return $this->authSteps($request, $loginData);
    }

    private function authSteps(Request $request, array $loginData): Response|RedirectResponse|InertiaResponse
    {
        if (empty($loginData)) {
            return Inertia::render('login', []);
        }

        return match ($loginData['code']) {
            "OK" => $this->nextSteps($request, $loginData),
            default => redirect()->route('login')->with('data', [
                'code' => $loginData['code'],
                'message' => $loginData['msg']
            ]),
        };
    }

    private function nextSteps(Request $request, array $loginData): Response|RedirectResponse|InertiaResponse
    {
        $spectrumData = $this->rsiService->spectrum();

        if ($spectrumData['success'] == 0) {
            return redirect()
                ->route('login')
                ->with('data', [
                    'code' => $spectrumData['code'],
                    'message' => $spectrumData['msg']
                ]);
        }

        $gameData = $this->rsiService->games();

        if ($gameData['success'] == 0) {
            return redirect()
                ->route('login')
                ->with('data', [
                    'code' => $gameData['code'],
                    'message' => $gameData['msg']
                ]);
        }

        $gameDataData = array_key_exists('data', $gameData) ? $gameData['data'] : '';
        $libraryData = $this->rsiService->library($gameDataData);

        if ($libraryData['success'] == 0) {
            return redirect()
                ->route('login')
                ->with('data', [
                    'code' => $libraryData['code'],
                    'message' => $libraryData['msg']
                ]);
        }

        $releaseData = [];
        $games = array_key_exists('data', $libraryData) && array_key_exists('games', $libraryData['data']) ? $libraryData['data']['games'] : [];

        foreach ($games as $game) {
            foreach ($game['channels'] as $channel) {
                $channelId = array_key_exists('id', $channel) ? $channel['id'] : '';
                $gameId =  array_key_exists('id', $channel) ? $game['id'] : '';

                $release = $this->rsiService->release($gameDataData, $channelId, $gameId);

                if ($release['success'] == 0) {
                    continue;
                }

                $releaseData[] = $release['data'];
            }
        }

        $session = $request->session();
        $device = $this->rsiService->getHeaders($session);

        $data = array_merge(
            [
                'username' => $this->rsiService->getUsername($session),
                'session_id' => $device[config('services.rsi.header.two.key')]
            ],
            $this->refineAccountData($loginData),
            $this->refineGameData($gameData),
            $this->refineSpectrumData($spectrumData),
            $this->refineLibraryData($libraryData),
            [
                'releases' => $releaseData
            ]
        );

        unset($libraryData);
        unset($spectrumData);
        unset($gameData);
        unset($libraryData);
        unset($releaseData);

        $user = $this->userService->store($data['account_id'], $data);

        Auth::loginUsingId($user->id);
        $request->session()->regenerateToken();

        return redirect()->intended(route('index'));
    }

    private function refineAccountData(array $response): array|null
    {
        if ($response['success'] !== 1)
        {
            return null;
        }

        $data = $response['data'];

        return [
            'account_id' => array_key_exists('account_id', $data) ? $data['account_id']: null,
            'nickname' => array_key_exists('nickname', $data) ? $data['nickname'] : null,
            'display_name' => array_key_exists('displayname', $data) ? $data['displayname'] : null,
            'badges' => array_key_exists('badges', $data) ? $data['badges'] : []
        ];
    }

    private function refineGameData(array $response): array|null
    {
        if ($response['success'] !== 1)
        {
            return null;
        }

        $data = $response['data'];

        return [
            'session_data' => $data
        ];
    }

    private function refineSpectrumData(array $response): array|null
    {
        if ($response['success'] !== 1)
        {
            return null;
        }

        $data = $response['data'];
        $organizations = [];

        if (count($data['communities']) >= 2) {
            foreach ($data['communities'] as $community) {
                if ($community['id'] == 1) continue;

                $organizations[] = [
                    'id' => array_key_exists('id', $community) ? $community['id'] : null,
                    'name' => array_key_exists('name', $community) ? $community['name'] : null,
                    'avatar' => array_key_exists('avatar', $community) ? $community['avatar'] : null,
                    'banner' => array_key_exists('banner', $community) ? $community['banner'] : null
                ];
            }
        }

        return [
            'avatar' => array_key_exists('member', $data) && array_key_exists('avatar', $data['member']) ? $data['member']['avatar'] : null,
            'organizations' => $organizations,
        ];
    }

    private function refineLibraryData(array $response): array|null
    {
        if ($response['success'] !== 1)
        {
            return null;
        }

        $games = [];
        $data = $response['data'];

        foreach ($data['games'] as $game) {
            $channels = [];

            foreach ($game['channels'] as $channel) {
                $channels[] = [
                    'id' => array_key_exists('id', $channel) ? $channel['id'] : null,
                    'name' => array_key_exists('name', $channel) ? $channel['name'] : null,
                    'nid' => array_key_exists('nid', $channel) ? $channel['nid'] : null
                ];
            }

            $games[] = [
                'id' => array_key_exists('id', $game) ? $game['id'] : null,
                'name' => array_key_exists('id', $game) ? $game['name'] : null,
                'channels' => $channels,
            ];
        }

        return [
            'games' => $games
        ];
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
