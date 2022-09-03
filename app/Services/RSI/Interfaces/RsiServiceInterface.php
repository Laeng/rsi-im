<?php

namespace App\Services\RSI\Interfaces;

use App\Models\Device;

interface RsiServiceInterface extends RsiServiceComponentInterface
{
    /**
     * Get the status of RSI.
     *
     * @return array
     */
    public function status(): array;

    /**
     * Get sign in results.
     *
     * @param Device $device
     * @param string $username RSI Account username (It should receive plain text as well as email.)
     * @param string $password RSI Account password
     * @param string $captcha CAPTCHA (it must enter the characters contained in the image generated via captcha method.)
     * @return array
     */
    public function signIn(Device $device, string $username, string $password, string $captcha): array;

    /**
     * Close the session for the safety of user's account.
     *
     * @param Device $device
     * @return array
     */
    public function signOut(Device $device): array;

    /**
     * Send the verification code entered by the user and receive the result.
     *
     * @param Device $device
     * @param string $code
     * @param string $duration
     * @return array
     */
    public function verifyMultiFactor(Device $device, string $code, string $duration): array;

    /**
     * Get the captcha image.
     *
     * @param Device $device
     * @return array
     */
    public function getCaptcha(Device $device): array;

    /**
     * Get permission to access user owned games information
     *
     * @param Device $device
     * @return array
     */
    public function getGames(Device $device): array;

    /**
     * Get user owned games
     *
     * @param Device $device
     * @param string $claims
     * @return array
     */
    public function getLibrary(Device $device, string $claims): array;

    /**
     * Get game version
     *
     * @param Device $device
     * @param string $claims
     * @param string $channel
     * @param string $game
     * @return array
     */
    public function getRelease(Device $device, string $claims, string $game, string $channel): array;

    /**
     * Get the spectrum data. Among them, only collect organization information.
     *
     * @param Device $device
     * @return array
     */
    public function getSpectrum(Device $device): array;


    /**
     * Check session exists
     *
     * @param Device $device
     * @return array
     */
    public function check(Device $device): array;
}
