<?php

namespace App\Services\RSI;

use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RsiService
{
    /*
     * I have hidden the detailed paths to protect the CIG's endpoint.
     * And I hope your understanding as this is a decision for your account and their security.
     * Thank you.
     *
     * - laeng
     */

    readonly private string $base;

    public function __construct()
    {
        /*
         * Base endpoint.
         * It is the same as the base address of RSI. This is the basis.
         */
        $this->base = 'https://robertsspaceindustries.com';
    }

    public function status(): array
    {
        $url = $this->base . config('services.rsi.main.status');
        $response = Http::post($url, []);

        return [];
    }

    public function login(string $username, string $password, string $captcha = ''): array
    {
        $url = $this->base . config('services.rsi.main.login');
        $headerOneValue = Str::lower(Str::random(config('services.rsi.header.one.length')));

        $response = Http::acceptJson()->timeout(1)->withHeaders([
            config('services.rsi.header.one.key') => $headerOneValue,
        ])->post($url, [
            'username' => $username,
            'password' => $password,
            'captcha' => $captcha
        ]);

        return $this->getResult($response);
    }

    public function captcha(): array
    {
        $url = $this->base . config('services.rsi.main.captcha');
        $session = request()->session();

        $response = Http::accept('image/png')->timeout(1)->withHeaders($this->getHeaders($session))->post($url);

        if (!empty($response->body())) {
            return [
                'success' => 1,
                'code' => 'OK',
                'image' => 'data:image/png;base64,'.base64_encode($response)
            ];
        } else {
            if ($response->serverError()) {
                return [
                    'success' => 0,
                    'code' => 'ErrUnknownServerError'
                ];
            }

            if ($response->clientError()) {
                return [
                    'success' => 0,
                    'code' => 'ErrUnknownClientError'
                ];
            }
        }

        return [];
    }

    public function multiFactor(string $code, string $duration): array
    {
        $url = $this->base . config('services.rsi.main.multi-factor');
        $session = request()->session();

        $response = Http::acceptJson()->timeout(1)->withHeaders($this->getHeaders($session))->post($url, [
            'code' => $code,
            'device_name' => config('app.name'),
            'device_type' => 'computer',
            'duration' => $duration
        ]);

        return $this->getResult($response);
    }

    public function games(): array
    {
        $url = $this->base . config('services.rsi.main.games');
        $session = request()->session();

        $response = Http::acceptJson()->timeout(1)->withHeaders($this->getHeaders($session))->post($url, []);

        return $this->getResult($response);
    }

    public function library(string $claims): array
    {
        $url = $this->base . config('services.rsi.main.library');
        $session = request()->session();

        $response = Http::acceptJson()->timeout(1)->withHeaders($this->getHeaders($session))->post($url, [
            'claims' => $claims
        ]);

        return $this->getResult($response);
    }


    public function spectrum(): array
    {
        $url = $this->base . config('services.rsi.spectrum.auth');
        $session = request()->session();

        $response = Http::acceptJson()->timeout(1)->withHeaders($this->getHeaders($session))->post($url, []);

        if (!empty($response->body())) {
            return json_decode($response->body(), true);
        } else {
            if ($response->serverError()) {
                return [
                    'success' => 0,
                    'code' => 'ErrUnknownServerError'
                ];
            }

            if ($response->clientError()) {
                return [
                    'success' => 0,
                    'code' => 'ErrUnknownClientError'
                ];
            }
        }
        return [];
    }

    public function getHeaders(Session $session): array
    {
        return $session->get('rsi');
    }

    private function setHeaders(Session $session, $oneValue, $twoValue): void
    {
        $session->put('rsi', [
            config('services.rsi.header.one.title') => $oneValue,
            config('services.rsi.header.two.title') => $twoValue
        ]);
    }

    private function getResult(ClientResponse $response): array
    {
        if (!empty($response->body())) {
            return json_decode($response->body(), true);
        } else {
            if ($response->serverError()) {
                return [
                    'success' => 0,
                    'code' => 'ErrUnknownServerError',
                    'msg' => ''
                ];
            }

            if ($response->clientError()) {
                return [
                    'success' => 0,
                    'code' => 'ErrUnknownClientError',
                    'msg' => ''
                ];
            }
        }
        return [];
    }
}
