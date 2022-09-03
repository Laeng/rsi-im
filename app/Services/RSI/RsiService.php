<?php

namespace App\Services\RSI;

use App\Models\Device;
use App\Repositories\Device\Interfaces\DeviceRepositoryInterface;
use App\Services\RSI\Interfaces\RsiServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RsiService extends RsiServiceComponent implements RsiServiceInterface
{
    public function __construct(DeviceRepositoryInterface $deviceRepository)
    {
        parent::__construct($deviceRepository);
    }

    public function status(): array
    {
        // TODO: Implement status() method.
        return [];
    }

    public function signIn(Device $device, string $username, string $password, string $captcha): array
    {
        $pathName = config('services.rsi.main.sign-in');

        return $this->postRequest($device, $pathName, [
            'username' => $username,
            'password' => $password,
            'captcha' => $captcha
        ]);
    }

    public function signOut(Device $device): array
    {
        $pathName = config('services.rsi.main.sign-out');

        return $this->postRequest($device, $pathName);
    }

    public function verifyMultiFactor(Device $device, string $code, string $duration): array
    {
        $pathName = config('services.rsi.main.multi-factor');

        return $this->postRequest($device, $pathName, [
            'code' => $code,
            'duration' => $duration,
            'device_type' => 'computer',
            'device_name' => config('app.name')
        ]);
    }

    public function getCaptcha(Device $device): array
    {
        $pathName = config('services.rsi.main.captcha');

        return $this->postRequestImage($device, $pathName);
    }

    public function getGames(Device $device): array
    {
        $pathName = config('services.rsi.main.games');

        return $this->postRequest($device, $pathName);
    }

    public function getLibrary(Device $device, string $claims): array
    {
        $pathName = config('services.rsi.main.library');
        $response = $this->postRequest($device, $pathName, [
            'claims' => $claims
        ]);

        $games = [];
        $data = $response['data'];

        if (key_exists('games', $data)) {
            foreach ($data['games'] as $game) {
                $channels = [];

                if (key_exists('channels', $game)) {
                    foreach ($game['channels'] as $channel) {
                        $channels[] = [
                            'id' => key_exists('id', $channels) ? $channel['id']: null,
                            'name' => key_exists('name', $channels) ? $channels['name'] : null,
                            'nid' => key_exists('nid', $channels) ? $channels['nid'] : null
                        ];
                    }
                }

                $games[] = [
                    'id' => key_exists('id', $game) ? $game['id'] : null,
                    'name' => key_exists('id', $game) ? $game['name'] : null,
                    'channels' => $channels,
                ];
            }

            $data['games'] = $games;
            $response['data'] = $data;
        }

        return $response;
    }

    public function getRelease(Device $device, string $claims, string $channel, string $game): array
    {
        $pathName = config('services.rsi.main.library');

        return $this->postRequest($device, $pathName, [
            'claims' => $claims,
            'channelId' => $channel,
            'gameId' => $game
        ]);
    }

    public function getSpectrum(Device $device): array
    {
        $pathName = config('services.rsi.spectrum.auth');
        $response = $this->postRequest($device, $pathName);
        $data = $response['data'];

        $newData = [
            'avatar' => null,
            'organizations' => [],
            'roles' => []
        ];

        if (key_exists('member', $data)) {
            $member = $data['member'];
            $newData['avatar'] = key_exists('avatar', $member) ? $member['avatar'] : null;
        }

        if (key_exists('communities', $data) && count($data['communities']) > 1) {
            $organizations = [];

            foreach ($data['communities'] as $community) {
                if ($community['id'] == 1) continue;

                $organizations[] = [
                    'id' => key_exists('id', $community) ? $community['id'] : null,
                    'name' => key_exists('name', $community) ? $community['name'] : null,
                    'avatar' => key_exists('avatar', $community) ? $community['avatar'] : null,
                    'banner' => key_exists('banner', $community) ? $community['banner'] : null
                ];
            }

            $newData['organizations'] = $organizations;
        }

        $roles = [];

        if (key_exists('roles', $data)) {
            foreach ($data['roles'] as $key => $role) {
                if ($key === 1) {
                    foreach ($role as $item) {
                        switch ($item) {
                            case "1":
                            case "2":
                                if (!in_array('staff', $roles)) $roles[] = 'staff';
                                break;
                            case "3":
                                if (!in_array('backer', $roles)) $roles[] = 'backer';
                                break;
                            case "5":
                            case "9":
                                if (!in_array('subscriber', $roles)) $roles[] = 'subscriber';
                                break;
                            case "6":
                            case "10":
                                if (!in_array('concierge', $roles)) $roles[] = 'concierge';
                                break;
                            case "7":
                                if (!in_array('evocati', $roles)) $roles[] = 'evocati';
                                break;
                            case "137732":
                                if (!in_array('mmhc', $roles)) $roles[] = 'mmhc';
                                break;
                        }

                    }
                    break;
                }
            }

            $newData['roles'] = $roles;
        }

        $data['data'] = $newData;

        return $data;
    }
}
