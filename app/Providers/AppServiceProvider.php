<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;

use App\Models\Kunjungan;
use App\Models\Tamu;
use App\Models\User;
use App\Observers\KunjunganObserver;
use App\Observers\TamuObserver;
use App\Observers\UserObserver;
use App\Policies\KunjunganPolicy;
use App\Policies\TamuPolicy;
use App\Policies\UserPolicy;
use App\Repositories\AuditLogRepository;
use App\Repositories\Contracts\KunjunganRepositoryInterface;
use App\Repositories\Contracts\TamuRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\KunjunganRepository;
use App\Repositories\TamuRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind Repository Interfaces to Implementations
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TamuRepositoryInterface::class, TamuRepository::class);
        $this->app->bind(KunjunganRepositoryInterface::class, KunjunganRepository::class);

        // Singleton for AuditLogRepository
        $this->app->singleton(AuditLogRepository::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        // Register Observers
        User::observe(UserObserver::class);
        Tamu::observe(TamuObserver::class);
        Kunjungan::observe(KunjunganObserver::class);

        // Register Policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Tamu::class, TamuPolicy::class);
        Gate::policy(Kunjungan::class, KunjunganPolicy::class);

        // Register @active Blade directive for sidebar nav highlighting
        \Illuminate\Support\Facades\Blade::directive('active', function (string $pattern) {
            return "<?php echo request()->is({$pattern}) ? 'active' : ''; ?>";
        });
    }
}
