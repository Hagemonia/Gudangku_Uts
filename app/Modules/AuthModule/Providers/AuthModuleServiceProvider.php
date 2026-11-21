<?php

namespace App\Modules\AuthModule\Providers;

use App\Modules\AuthModule\Http\Middleware\EnsureUserHasRole;
use App\Modules\AuthModule\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AuthModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        config(['auth.providers.users.model' => User::class]);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'access');

        Route::aliasMiddleware('role', EnsureUserHasRole::class);

        RateLimiter::for('auth-attempts', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });
    }
}
