<?php

namespace App\Services\RSI\Interfaces;

use App\Models\Device;

interface RsiServiceComponentInterface
{
    public function getDevice(string $type, string $value, array $attributes = []): ?Device;

    public function setDevice(string $type, string $value, array $attributes = []): bool;

    public function createDeviceHash(string $ip, string $username): string;

    public function postRequest(Device $device, string $pathName, array $jsonBody = []): array;

    public function postRequestImage(Device $device, string $pathName, array $jsonBody = [], string $type = 'png'): array;

}
