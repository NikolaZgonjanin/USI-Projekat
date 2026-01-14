<?php

namespace App\Providers;

use App\Models\FirmwareVersion;
use App\Models\Project;
use App\Models\SupportRequest;
use App\Models\User;
use App\Policies\FirmwareVersionPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\SupportRequestPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Project::class => ProjectPolicy::class,
        FirmwareVersion::class => FirmwareVersionPolicy::class,
        SupportRequest::class => SupportRequestPolicy::class,
    ];

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
        $this->registerPolicies();
    }
}
