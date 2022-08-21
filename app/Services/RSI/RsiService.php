<?php

namespace App\Services\RSI;

use App\Services\RSI\Interfaces\RsiServiceInterface;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RsiService implements RsiServiceInterface
{
    /*
     * I have hidden the detailed paths to protect the CIG's endpoint.
     * And I hope your understanding as this is a decision for your account and their security.
     * Thank you.
     *
     * by laeng
     */


    /**
     * It's the same as the website URL of RSI.
     * All APIs implemented here are based on this address.
     *
     * @var string Base Endpoint
     */
    private string $base = 'https://robertsspaceindustries.com';
    private string $sessionName = 'RSI_SESSION';

    public function status(): array
    {
        $url = $this->base . config('services.rsi.main.status');
        $response = Http::post($url, []);

        return [];
    }

    public function login(string $username, string $password, string $captcha = ''): array
    {
        $url = $this->base . config('services.rsi.main.login');
        $session = request()->session();
        $header = $this->getHeaders($session);

        $response = Http::acceptJson()
            ->timeout(5)
            ->withHeaders($header)
            ->post($url, [
                'username' => $username,
                'password' => $password,
                'captcha' => $captcha
            ]);

        return $this->getResult($session, $response);
    }

    public function captcha(): array
    {
        $url = $this->base . config('services.rsi.main.captcha');
        $session = request()->session();

        $response = Http::accept('image/png')
            ->timeout(5)
            ->withHeaders($this->getHeaders($session))
            ->post($url, []);

        if (!empty($response->body())) {
            return [
                'success' => 1,
                'code' => 'OK',
                'data' => [
                    'image' => 'data:image/png;base64,'.base64_encode($response)
                ]
            ];
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

    public function multiFactor(string $code, string $duration): array
    {
        $url = $this->base . config('services.rsi.main.multi-factor');
        $session = request()->session();
        $header = $this->getHeaders($session);

        $response = Http::acceptJson()
            ->timeout(5)
            ->withHeaders($header)
            ->post($url, [
                'code' => $code,
                'device_name' => config('app.name'),
                'device_type' => 'computer',
                'duration' => $duration
            ]);

        return $this->getResult($session, $response);
    }

    public function games(): array
    {
        $url = $this->base . config('services.rsi.main.games');
        $session = request()->session();

        $response = Http::acceptJson()
            ->timeout(5)
            ->withHeaders($this->getHeaders($session))
            ->post($url, []);

        return $this->getResult($session, $response);
    }

    public function library(string $claims): array
    {
        $url = $this->base . config('services.rsi.main.library');
        $session = request()->session();

        $response = Http::acceptJson()
            ->timeout(5)
            ->withHeaders($this->getHeaders($session))
            ->post($url, [
                'claims' => $claims
            ]);

        return $this->getResult($session, $response);
    }


    public function spectrum(): array
    {
        $url = $this->base . config('services.rsi.spectrum.auth');
        $session = request()->session();

        $response = Http::acceptJson()
            ->timeout(5)
            ->withHeaders($this->getHeaders($session))
            ->post($url, []);

        return $this->getResult($session, $response);
    }

    public function logout(): array
    {
        $url = $this->base . config('services.rsi.main.logout');
        $session = request()->session();

        $response = Http::acceptJson()
            ->timeout(5)
            ->withHeaders($this->getHeaders($session))
            ->post($url, []);

        return $this->getResult($session, $response);
    }

    public function getHeaders(Session $session): array
    {
        $header = $session->get($this->sessionName);

        if (is_null($header) || !array_key_exists(config('services.rsi.header.one.key'), $header)) {
            $one = Str::lower(Str::random(config('services.rsi.header.one.length')));
            $this->setHeaders($session, $one, '');

            $header = $this->getHeaders($session);
        }

        return $header;
    }

    private function setHeaders(Session $session, $one, $two): void
    {
        $session->put($this->sessionName, [
            config('services.rsi.header.one.key') => $one,
            config('services.rsi.header.two.key') => $two
        ]);
    }

    private function getResult(Session $session, ClientResponse $response): array
    {
        if (!empty($response->body())) {
            $header = $this->getHeaders($session);
            $data = json_decode($response->body(), true) ?? [];

            if (isset($data['data']['session_id'])) {
                $this->setHeaders(
                    $session,
                    $header[config('services.rsi.header.one.key')],
                    $data['data']['session_id']
                );
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
}
