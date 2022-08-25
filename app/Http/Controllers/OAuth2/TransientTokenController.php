<?php

namespace App\Http\Controllers\OAuth2;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\ApiTokenCookieFactory;

class TransientTokenController extends \Laravel\Passport\Http\Controllers\TransientTokenController
{
    /**
     * The cookie factory instance.
     *
     * @var ApiTokenCookieFactory
     */
    protected $cookieFactory;

    /**
     * Create a new controller instance.
     *
     * @param ApiTokenCookieFactory $cookieFactory
     * @return void
     */
    public function __construct(ApiTokenCookieFactory $cookieFactory)
    {
        parent::__construct($cookieFactory);
        $this->cookieFactory = $cookieFactory;
    }

    /*
    public function refresh(Request $request)
    {
        return new Response('Not Implemented', '501');
    }
    */
}
