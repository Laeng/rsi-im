<?php

namespace App\Listeners;

use App\Enums\UserLogType;
use App\Events\interfaces\SignEventInterface;
use App\Events\SignInEvent;
use App\Events\SignOutEvent;
use App\Repositories\User\Interfaces\UserLogRepositoryInterface;
use Illuminate\Events\Dispatcher;

class LogUserSignSubscriber
{
    private UserLogRepositoryInterface $userLogRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserLogRepositoryInterface $userLogRepository)
    {
        $this->userLogRepository = $userLogRepository;
    }

    /**
     * @param SignEventInterface $event
     * @return void
     */
    public function writeLog(SignEventInterface $event): void
    {
        $user = $event->getUser();
        $request = $event->getRequest();

        $type = match($event->getName()) {
            SignInEvent::class => UserLogType::SignIn,
            SignOutEvent::class => UserLogType::SignOut
        };

        $this->userLogRepository->create([
            'user_id' => $user->getAttribute('id'),
            'type' => $type,
            'ip' => $request->getClientIp(),
            'data' => [
                'useragent' => $request->userAgent()
            ]
        ]);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            SignInEvent::class,
            [LogUserSignSubscriber::class, 'writeLog']
        );

        $events->listen(
            SignOutEvent::class,
            [LogUserSignSubscriber::class, 'writeLog']
        );
    }
}
