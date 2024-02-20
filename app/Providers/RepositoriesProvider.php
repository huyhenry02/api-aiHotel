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
            'Hotel',
            'Example',
            'Reservation',
            'Statistic',
            'Service',
            'RoomType',
            'Room'
        ];
        foreach ($repositories as $repository) {
            $this->app->bind('App\Modules\\' . $repository . '\Repositories\Interfaces\\' . $repository . 'Interface',
                'App\Modules\\' . $repository . '\Repositories\\' . $repository . 'Repository'
            );
        }
    }
}
