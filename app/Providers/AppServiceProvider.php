<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $Repositories = ['Task'];

        foreach ($Repositories as $repo) {
            $this->app->bind("App\Repository\Interfaces\\" . $repo . "RepositoryInterface", "App\Repository\\" . $repo . "Repository");
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
