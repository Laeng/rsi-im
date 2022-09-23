<?php

namespace App\Events;

use App\Events\interfaces\SignEventInterface;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class SignOutEvent extends SignEvent implements SignEventInterface
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $name = SignInEvent::class;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Request $request, User $user)
    {
        parent::__construct($request, $user);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
