<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Enum\RoleEnum;
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
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        if (request()->is(config('app.admin_path').'/*')) {
            /**
             * Super Admin Have Access To All Of Permissions
             */
            Gate::before(function ($user, $ability) {
                return $user->hasRole(RoleEnum::SUPER_ADMIN) ? true : null;
            });
        }
    }
}
