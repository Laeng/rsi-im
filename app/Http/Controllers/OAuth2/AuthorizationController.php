<?php

namespace App\Http\Controllers\OAuth2;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Laravel\Passport\Bridge\User as PassportUser;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Nyholm\Psr7\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;

class AuthorizationController extends  \Laravel\Passport\Http\Controllers\AuthorizationController
{
    use HandlesOAuthErrors;

    /**
     * The authorization server.
     *
     * @var AuthorizationServer
     */
    protected $server;

    /**
     * The response factory implementation.
     *
     * @var ResponseFactory
     */
    protected $response;

    /**
     * Create a new controller instance.
     *
     * @param AuthorizationServer $server
     * @param ResponseFactory $response
     * @return void
     */
    public function __construct(AuthorizationServer $server, ResponseFactory $response)
    {
        parent::__construct($server, $response);
        $this->server = $server;
        $this->response = $response;
    }

    /**
     * Authorize a client to access the user's account.
     *
     * @param ServerRequestInterface $psrRequest
     * @param Request $request
     * @param ClientRepository $clients
     * @param TokenRepository $tokens
     * @return Response|InertiaResponse
     */
    public function authorize(ServerRequestInterface $psrRequest,
                              Request $request,
                              ClientRepository $clients,
                              TokenRepository $tokens): Response|InertiaResponse
    {
        $authRequest = $this->withErrorHandling(function () use ($psrRequest) {
            return $this->server->validateAuthorizationRequest($psrRequest);
        });

        $scopes = $this->parseScopes($authRequest);

        $token = $tokens->findValidToken(
            $user = $request->user(),
            $client = $clients->find($authRequest->getClient()->getIdentifier())
        );

        if (($token && $token->scopes === collect($scopes)->pluck('id')->all()) ||
            $client->skipsAuthorization()) {
            return $this->approveRequest($authRequest, $user);
        }

        $request->session()->put('authToken', $authToken = Str::random());
        $request->session()->put('authRequest', $authRequest);

        return Inertia::render('auth/authorize', [
            'client' => [
                'id' => $client->getAttribute('id'),
                'name' => $client->getAttribute('name')
            ],
            'request' => [
                'state' => $request->get('state')
            ],
            'authToken' => $authToken,
            'scopes' => $scopes
        ]);
    }

}
