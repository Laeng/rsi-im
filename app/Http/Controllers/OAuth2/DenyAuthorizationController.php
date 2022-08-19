<?php

namespace App\Http\Controllers\OAuth2;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Passport\Http\Controllers\RetrievesAuthRequestFromSession;

class DenyAuthorizationController
{
    use RetrievesAuthRequestFromSession;

    /**
     * The response factory implementation.
     *
     * @var ResponseFactory
     */
    protected ResponseFactory $response;

    /**
     * Create a new controller instance.
     *
     * @param ResponseFactory $response
     * @return void
     */
    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    /**
     * Deny the authorization request.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function deny(Request $request): RedirectResponse
    {
        $this->assertValidAuthToken($request);

        $authRequest = $this->getAuthRequestFromSession($request);

        $clientUris = Arr::wrap($authRequest->getClient()->getRedirectUri());

        if (! in_array($uri = $authRequest->getRedirectUri(), $clientUris)) {
            $uri = Arr::first($clientUris);
        }

        $separator = $authRequest->getGrantTypeId() === 'implicit' ? '#' : (str_contains($uri, '?') ? '&' : '?');

        return $this->response->redirectTo(
            $uri.$separator.'error=access_denied&state='.$request->input('state')
        );
    }
}
