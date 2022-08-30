<?php

namespace App\Services\RSI\Interfaces;

use App\Models\Device;

interface RsiServiceComponentInterface
{
    public function getDevice(string $type, string $value, array $attributes = []): ?Device;

    public function createDeviceHash(string $ip, string $username): string;
}
