<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoriesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $repositories = [
            'User',
            'Role',
        ];
        foreach ($repositories as $repository) {
            $this->app->bind('App\Repositories\Interfaces\\' . $repository . 'Interface',
                'App\Repositories\\' . $repository . 'Repository'
            );
        }

    }
}
