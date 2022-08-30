<?php

namespace App\Services\RSI;

use App\Models\Device;
use App\Repositories\Device\Interfaces\DeviceRepositoryInterface;
use App\Services\RSI\Interfaces\RsiServiceComponentInterface;
use App\Services\RSI\Interfaces\RsiServiceInterface;
use Illuminate\Support\Facades\Http;

class RsiServiceComponent implements RsiServiceComponentInterface
{
    private DeviceRepositoryInterface $deviceRepository;

    public function __construct(DeviceRepositoryInterface $deviceRepository)
    {
        $this->deviceRepository = $deviceRepository;
    }

    private function postRequest(Device $device, string $pathName, array $jsonBody): array
    {
        if (!preg_match('/^\//', $pathName)) {
            return [
                'success' => 0,
                'code' => 'ErrNotCorrectPathName',
                'message' => ''
            ];
        }

        $header = $device->getAttribute('data');
        $host = 'https://robertsspaceindustries.com';
        $response = Http::acceptJson()
            ->withHeaders($device->getAttribute('data'))
            ->timeout(5)
            ->post($host . $pathName, $jsonBody);

        if (!empty($response->body())) {
            $data = json_decode($response->body(), true) ?? [];

            if (isset($data['data']['device_id'])) {
                $header[config('services.rsi.header.one.key')] = $data['data']['device_id'];
            }

            if (isset($data['data']['session_id'])) {
                $header[config('services.rsi.header.two.key')] = $data['data']['session_id'];
            }

            if (isset($data['data']['device_id']) || isset($data['data']['session_id'])) {
                $this->setDevice('id', $device->getAttribute('id'), ['data' => $header]);
            }

            return $data;

        } else {
            $code = 'ErrUnknown';

            if ($response->serverError()) $code = 'ErrServer';
            if ($response->clientError()) $code = 'ErrClient';

            return [
                'success' => 0,
                'code' => $code,
                'data' => [
                    'image' => 'data:image/png;base64,'
                ]
            ];
        }
    }

    public function getDevice(string $type, string $value, array $attributes = []): ?Device
    {
        $device = $this->getDeviceModel($type, $value);

        if (is_null($device)) {
            $device = $this->setDevice($type, $value, $attributes);
        }

        return $device;
    }

    private function setDevice(string $type, string $value, array $attributes = []) : bool
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
}
