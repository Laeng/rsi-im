<?php

namespace App\Services\RSI;

use App\Models\Device;
use App\Repositories\Device\Interfaces\DeviceRepositoryInterface;
use App\Services\RSI\Interfaces\RsiServiceComponentInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RsiServiceComponent implements RsiServiceComponentInterface
{
    private DeviceRepositoryInterface $deviceRepository;
    private string $hostName = 'https://robertsspaceindustries.com';

    public function __construct(DeviceRepositoryInterface $deviceRepository)
    {
        $this->deviceRepository = $deviceRepository;
    }

    public function postRequest(Device $device, string $pathName, array $jsonBody = []): array
    {
        if (!preg_match('/^\//', $pathName)) {
            return [
                'success' => 0,
                'code' => 'ErrNotCorrectPathName',
                'msg' => ''
            ];
        }

        $header = $device->getAttribute('data');
        $response = Http::acceptJson()
            ->withHeaders($header)
            ->timeout(30)
            ->post($this->hostName . $pathName, $jsonBody);

        $body = $response->body();

        if (!empty($body)) {
            $data = json_decode($response->body(), true) ?? [];

            if (count($data) === 0) {
                return [
                    'success' => 0,
                    'code' => 'ErrNotJsonResponse',
                    'msg' => 'Can not parse response'
                ];
            }

            if (isset($data['data']['device_id'])) {
                $header[config('services.rsi.header.one.key')] = $data['data']['device_id'];
            }

            if (isset($data['data']['session_id'])) {
                $header[config('services.rsi.header.two.key')] = $data['data']['session_id'];
            }

            if (isset($data['data']['device_id']) || isset($data['data']['session_id'])) {
                $this->setDevice('id', $device->getAttribute('id'), ['data' => $header]);
            }

            $data = $this->set($data, 'code');
            $data = $this->set($data, 'msg');

            return $data;

        } else {
            $code = 'ErrUnknown';

            if ($response->serverError()) $code = 'ErrServer';
            if ($response->clientError()) $code = 'ErrClient';

            return [
                'success' => 0,
                'code' => $code,
                'msg' => ''
            ];
        }
    }

    public function postRequestImage(Device $device, string $pathName, array $jsonBody = [], string $type = 'png'): array
    {
        if (!preg_match('/^\//', $pathName)) {
            return [
                'success' => 0,
                'code' => 'ErrNotCorrectPathName',
                'msg' => ''
            ];
        }

        $header = $device->getAttribute('data');
        $response = Http::accept("image/{$type}")
            ->withHeaders($header)
            ->timeout(30)
            ->post($this->hostName . $pathName, $jsonBody);

        if (!empty($response->body())) {
            return [
                'success' => 1,
                'code' => 'OK',
                'data' => [
                    'image' => "data:image/{$type};base64,".base64_encode($response)
                ]
            ];
        } else {
            $code = 'ErrUnknown';

            if ($response->serverError()) $code = 'ErrServer';
            if ($response->clientError()) $code = 'ErrClient';

            return [
                'success' => 0,
                'code' => $code,
                'msg' => '',
                'data' => [
                    'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='
                ]
            ];
        }
    }

    public function getDevice(string $type, string $value, array $attributes = []): ?Device
    {
        $device = $this->getDeviceModel($type, $value);

        if (is_null($device)) {
            if ($this->setDevice($type, $value, $attributes)) {
                $device = $this->getDeviceModel($type, $value);
            }
        }

        return $device;
    }

    public function setDevice(string $type, string $value, array $attributes = []) : bool
    {
        $device = $this->getDeviceModel($type, $value);

        $user_id = key_exists('user_id', $attributes) ? $attributes['user_id'] : null;
        $user_id = $type === 'user_id' ? $value : $user_id;

        $hash = key_exists('hash', $attributes) ? $attributes['hash'] : null;
        $hash = $type === 'hash' ? $value : $hash;

        $expireAt = key_exists('expired_at', $attributes) ? $attributes['expired_at'] : now()->addHour();

        if (key_exists('duration', $attributes)) {
            $expireAt = match ($attributes['duration']) {
                'session' => now()->addHours(2),
                'day' => now()->addDay(),
                'week' => now()->addWeek(),
                'month' => now()->addMonth(),
                'year' => now()->addYear(),
                default => now()->addHour()
            };

            unset($attributes['duration']);
        }

        if (is_null($device)) {
            $device = $this->deviceRepository->create([
                'user_id' => $user_id,
                'hash' => $hash,
                'data' => key_exists('data', $attributes) ? $attributes['data'] : [],
                'expired_at' => $expireAt
            ]);

            return !is_null($device);

        } else {
            return $this->deviceRepository->update($device->getAttribute('id'), $attributes);
        }
    }

    private function getDeviceModel(string $type, string $value): ?Device
    {
        return match (mb_strtolower($type)) {
            'id' => $this->deviceRepository->findById($value),
            'hash' => $this->deviceRepository->findByHash($value),
            'user_id' => $this->deviceRepository->findByUserId($value),
            default => null
        };
    }

    public function createDeviceHash(string $ip, string $username): string
    {
        $salt = config('app.key');
        return hash("sha256", "{$ip}/{$username}/{$salt}");
    }

    private function set(array $array, string $key, string $value = ''): array
    {
        if (!key_exists($key, $array)) {
            $array[$key] = $value;
        }

        return $array;
    }
}
