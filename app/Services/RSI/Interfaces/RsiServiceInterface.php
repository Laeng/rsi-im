<?php

namespace App\Services\RSI\Interfaces;

use Illuminate\Contracts\Session\Session;

interface RsiServiceInterface
{
    public function status(): array;
    public function login(string $username, string $password, string $captcha = ''): array;
    public function captcha(): array;
    public function multiFactor(string $code, string $duration): array;
    public function games(): array;
    public function library(string $claims): array;
    public function spectrum(): array;
    public function getHeaders(Session $session): array;
}
