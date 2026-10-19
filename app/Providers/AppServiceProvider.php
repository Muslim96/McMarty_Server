<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
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
        Paginator::useBootstrapFive();

        foreach (config('admin.abilities') as $ability) {
            Gate::define($ability, fn (User $user) => $user->hasAbility($ability));
        }

        Blade::directive('money', fn ($e) => "<?php echo number_format((float) ($e), 2, ',', ' ') . ' ' . config('admin.currency'); ?>");
    }
}
