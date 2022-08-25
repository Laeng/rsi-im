<?php

namespace App\Services\RSI\Interfaces;

use Illuminate\Contracts\Session\Session;

interface RsiServiceInterface
{
    /**
     * @return array
     *
     * @TODO Get the status of RSI.
     */
    public function status(): array;

    /**
     * Get login results.
     *
     * @param string $username RSI Account username (It should receive plain text as well as email.)
     * @param string $password RSI Account password
     * @param string $captcha  CAPTCHA (it must enter the characters contained in the image generated via captcha method.)
     * @return array
     */
    public function login(string $username, string $password, string $captcha = ''): array;

    /**
     * Get the captcha image.
     *
     * @return array
     *
     */
    public function captcha(): array;

    /**
     * Send the verification code entered by the user and receive the result.
     *
     * @param string $code
     * @param string $duration
     * @return array
     *
     */
    public function multiFactor(string $code, string $duration): array;

    /**
     * Get permission to access user owned games information
     *
     * @return array
     */
    public function games(): array;

    /**
     * Get user owned games
     *
     * @param string $claims
     * @return array
     */
    public function library(string $claims): array;

    /**
     * Get game version
     *
     * @param string $claims
     * @param string $channel
     * @param string $game
     * @return array
     */
    public function release(string $claims, string $channel, string $game): array;

    /**
     * Get Organizational Information
     *
     * @return array
     *
     */
    public function spectrum(): array;

    /**
     * Close the session for the safety of your account.
     *
     * @return array
     */
    public function logout(): array;

    public function getHeaders(Session $session): array;

    public function getUsername(Session $session): string;
}
