<?php

namespace App\Providers;

use App\Models\MetricQuery;
use App\Models\Module;
use App\Models\Role;
use App\Models\User;
use App\Policies\MetricQueryPolicy;
use App\Policies\ModulePolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Carbon\CarbonInterval;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Module::class, ModulePolicy::class);
        Gate::policy(MetricQuery::class, MetricQueryPolicy::class);

        Passport::authorizationView('auth.oauth-authorize');
        Passport::tokensExpireIn(CarbonInterval::days(15));
        Passport::refreshTokensExpireIn(CarbonInterval::days(30));
        Passport::personalAccessTokensExpireIn(CarbonInterval::months(6));
    }
}
