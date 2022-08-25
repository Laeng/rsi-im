<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::tokensCan([
            'launch-game' => 'Provides information to run the game. Third parties may execute the game using your account.',
            'list-of-games' => 'Provides a list of games you have pledge.',
            'list-of-organizations' => 'Provides a list of organizations to which you have joined.',
            'basic-info' => 'Provide your unique account number, nickname, display name, badge list, and avatar image.'
        ]);

        Passport::setDefaultScope([
            'basic-info',
        ]);
    }
}
