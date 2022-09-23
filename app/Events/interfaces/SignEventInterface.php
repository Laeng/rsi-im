<?php

namespace App\Events\interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface SignEventInterface
{
    public function getName(): string;
    public function getUser(): User;
    public function getRequest(): Request;
}
