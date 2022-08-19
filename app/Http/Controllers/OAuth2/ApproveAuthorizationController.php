<?php

namespace App\Http\Controllers\OAuth2;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\Http\Controllers\ConvertsPsrResponses;
use Laravel\Passport\Http\Controllers\RetrievesAuthRequestFromSession;
use League\OAuth2\Server\AuthorizationServer;
use Nyholm\Psr7\Response as Psr7Response;

class ApproveAuthorizationController
{
    use ConvertsPsrResponses, RetrievesAuthRequestFromSession;

    /**
     * The authorization server.
     *
     * @var AuthorizationServer
     */
    protected AuthorizationServer $server;

    /**
     * Create a new controller instance.
     *
     * @param AuthorizationServer $server
     * @return void
     */
    public function __construct(AuthorizationServer $server)
    {
        $this->server = $server;
    }

    /**
     * Approve the authorization request.
     *
     * @param Request $request
     * @return Response
     */
    public function approve(Request $request): Response
    {
        $this->assertValidAuthToken($request);

        $authRequest = $this->getAuthRequestFromSession($request);

        return $this->convertResponse(
            $this->server->completeAuthorizationRequest($authRequest, new Psr7Response)
        );
    }
}
