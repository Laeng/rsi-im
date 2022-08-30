<?php

namespace App\Services\RSI;

use App\Models\Device;
use App\Repositories\Device\Interfaces\DeviceRepositoryInterface;
use App\Services\RSI\Interfaces\RsiServiceInterface;
use Illuminate\Support\Facades\Http;

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
            'duration' => $duration
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

        if (key_exists('member', $data)) {
            $member = $data['member'];
            $extraData = [
                'avatar' => key_exists('avatar', $member)
            ];

            $data = array_merge($data, $extraData);
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

            $data = array_merge($data, $organizations);
        }

        return $data;
    }
}
