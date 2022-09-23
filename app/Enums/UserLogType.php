<?php

namespace App\Enums;

enum UserLogType: string
{
    case SignIn = 'sign_in';
    case SignOut = 'sign_out';
}
