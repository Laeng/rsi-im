<?php

namespace App\Http\Controllers\OAuth2;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\ApiTokenCookieFactory;

class TransientTokenController
{
    /**
     * The cookie factory instance.
     *
     * @var ApiTokenCookieFactory
     */
    protected ApiTokenCookieFactory $cookieFactory;

    /**
     * Create a new controller instance.
     *
     * @param ApiTokenCookieFactory $cookieFactory
     * @return void
     */
    public function __construct(ApiTokenCookieFactory $cookieFactory)
    {
        $this->cookieFactory = $cookieFactory;
    }

    /**
     * Get a fresh transient token cookie for the authenticated user.
     *
     * @param Request $request
     * @return Response
     */
    public function refresh(Request $request): Response
    {
        return (new Response('Refreshed.'))->withCookie($this->cookieFactory->make(
            $request->user()->getAuthIdentifier(), $request->session()->token()
        ));
    }
}
