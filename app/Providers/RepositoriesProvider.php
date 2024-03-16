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
            'File',
            'Example',
            'Reservation',
            'Statistic',
            'Service',
            'Invoice',
            'RoomType',
            'Room'
        ];
        foreach ($repositories as $repository) {
            $this->app->bind('App\Modules\\' . $repository . '\Repositories\Interfaces\\' . $repository . 'Interface',
                'App\Modules\\' . $repository . '\Repositories\\' . $repository . 'Repository'
            );
        }
        $subRepositories = [
            'Review.ResponseReview',
            'Review.Review',
        ];
        foreach ($subRepositories as $subRepository) {
            [$parentRepository, $subRepository] = explode('.', $subRepository);
            $this->app->bind('App\Modules\\' . $parentRepository . '\Repositories\Interfaces\\' . $subRepository . 'Interface',
                'App\Modules\\' . $parentRepository . '\Repositories\\' . $subRepository . 'Repository'
            );
        }

    }
}
