<?php

namespace App\Events;

use App\Events\interfaces\SignEventInterface;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class SignEvent implements SignEventInterface
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $name = 'UNKNOWN';
    private Request $request;
    public User $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel|array
    {
        return new PrivateChannel('channel-name');
    }
}
