<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Episode;
use App\Models\Podcast;
use App\Models\User;
use App\Policies\EpisodePolicy;
use App\Policies\PodcastPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
    Podcast::class => PodcastPolicy::class,
    Episode::class => EpisodePolicy::class,
    User::class => UserPolicy::class,

];


    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
