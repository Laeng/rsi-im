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

        switch ($data['code']) {
            case 'ErrMultiStepRequired':
            case 'ErrMultiStepExpired':
            case 'ErrMultiStepWrongCode':
                return Inertia::render('auth/multi-factor', $data);
            default:
                return Inertia::render('auth/basic', $data);
        }
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
            $request->get('captcha') ?? '',
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

        $loginData = $this->rsiService->multiFactor($request->get('code'), $request->get('duration'));

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
            return redirect()->route('login')->with('data', [
                'code' => $spectrumData['code'],
                'message' => $spectrumData['msg']
            ]);
        }

        $gameData = $this->rsiService->games();

        if ($gameData['success'] == 0) {
            return redirect()->route('login')->with('data', [
                'code' => $gameData['code'],
                'message' => $gameData['msg']
            ]);
        }

        $libraryData = $this->rsiService->library($gameData['data']);

        if ($libraryData['success'] == 0) {
            return redirect()->route('login')->with('data', [
                'code' => $libraryData['code'],
                'message' => $libraryData['msg']
            ]);
        }

        $data = array_merge(
            $this->refineAccountData($loginData),
            $this->refineSpectrumData($spectrumData),
            $this->refineLibraryData($libraryData)
        );

        unset($libraryData);
        unset($spectrumData);
        unset($gameData);
        unset($libraryData);

        $user = $this->userService->store($data['account_id'], $data);

        Auth::loginUsingId($user->id);
        $request->session()->regenerateToken();

        return redirect()->intended(route('app.index'));
    }

    private function refineAccountData(array $response): array|null
    {
        if ($response['success'] !== 1)
        {
            return null;
        }

        $data = $response['data'];

        return [
            'account_id' => $data['account_id'],
            'nickname' => $data['nickname'],
            'display_name' => $data['displayname'],
            'badges' => $data['badges']
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
                    'id' => $community['id'],
                    'name' => $community['name'],
                    'avatar' => $community['avatar'],
                    'banner' => $community['banner']
                ];
            }
        }

        return [
            'spectrum_id' => $data['member']['id'],
            'avatar' => $data['member']['avatar'],
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
                    'id' => $channel['id'],
                    'name' => $channel['name'],
                    'version' => $channel['version'],
                    'versionLabel' => $channel['versionLabel']
                ];
            }

            $games[] = [
                'id' => $game['id'],
                'name' => $game['name'],
                'channels' => $channels,
                'nid' => $game['nid']
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
