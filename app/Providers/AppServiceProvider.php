<?php

namespace App\Providers;

use App\Interfaces\Repositories\PatientRepositoryInterface;
use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Repositories\EloquentPatientRepository;
use App\Repositories\EloquentUserRepository;
use App\Support\Tenancy\TenantContext;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(
            TenantContext::class,
            fn() => new TenantContext()
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );

        $this->app->bind(
            PatientRepositoryInterface::class,
            EloquentPatientRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $email = Str::lower(
                (string) $request->input('email')
            );

            return Limit::perMinute(5)->by(
                $email . '|' . $request->ip()
            );
        });
    }
}
