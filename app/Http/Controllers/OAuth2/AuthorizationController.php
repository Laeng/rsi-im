<?php

namespace App\Http\Controllers\OAuth2;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Laravel\Passport\Bridge\User as PassportUser;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Nyholm\Psr7\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;

class AuthorizationController
{
    use HandlesOAuthErrors;

    /**
     * The authorization server.
     *
     * @var AuthorizationServer
     */
    protected AuthorizationServer $server;

    /**
     * The response factory implementation.
     *
     * @var ResponseFactory
     */
    protected ResponseFactory $response;

    /**
     * Create a new controller instance.
     *
     * @param AuthorizationServer $server
     * @param ResponseFactory $response
     * @return void
     */
    public function __construct(AuthorizationServer $server, ResponseFactory $response)
    {
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
     * @return Response
     */
    public function authorize(ServerRequestInterface $psrRequest,
                              Request $request,
                              ClientRepository $clients,
                              TokenRepository $tokens): Response
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

        return $this->response->view('passport::authorize', [
            'client' => $client,
            'user' => $user,
            'scopes' => $scopes,
            'request' => $request,
            'authToken' => $authToken,
        ]);
    }

    /**
     * Transform the authorization requests's scopes into Scope instances.
     *
     * @param  AuthorizationRequest  $authRequest
     * @return array
     */
    protected function parseScopes(AuthorizationRequest $authRequest): array
    {
        return Passport::scopesFor(
            collect($authRequest->getScopes())->map(function ($scope) {
                return $scope->getIdentifier();
            })->unique()->all()
        );
    }

    /**
     * Approve the authorization request.
     *
     * @param  AuthorizationRequest  $authRequest
     * @param Model $user
     * @return Response
     */
    protected function approveRequest(AuthorizationRequest $authRequest, Model $user): Response
    {
        $authRequest->setUser(new PassportUser($user->getAuthIdentifier()));

        $authRequest->setAuthorizationApproved(true);

        return $this->withErrorHandling(function () use ($authRequest) {
            return $this->convertResponse(
                $this->server->completeAuthorizationRequest($authRequest, new Psr7Response)
            );
        });
    }
}
