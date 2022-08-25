<?php

namespace App\Http\Controllers\OAuth2;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Passport\Http\Controllers\RetrievesAuthRequestFromSession;

class DenyAuthorizationController  extends \Laravel\Passport\Http\Controllers\DenyAuthorizationController
{
    use RetrievesAuthRequestFromSession;

    /**
     * The response factory implementation.
     *
     * @var ResponseFactory
     */
    protected $response;

    /**
     * Create a new controller instance.
     *
     * @param ResponseFactory $response
     * @return void
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);
        $this->response = $response;
    }

}
