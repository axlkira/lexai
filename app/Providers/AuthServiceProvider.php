<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\LegalCase;
use App\Policies\LegalCasePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        LegalCase::class => LegalCasePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Definir la política para los clientes
        Gate::define('view-client', function ($user, Client $client) {
            return $user->id === $client->user_id;
        });

        Gate::define('update-client', function ($user, Client $client) {
            return $user->id === $client->user_id;
        });
    }
}
