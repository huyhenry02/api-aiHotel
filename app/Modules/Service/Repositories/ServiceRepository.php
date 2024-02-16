<?php

namespace App\Modules\Service\Repositories;


use App\Modules\Service\Models\Service;
use App\Modules\Service\Repositories\Interfaces\ServiceInterface;
use App\Repositories\BaseRepository;


class ServiceRepository extends BaseRepository implements ServiceInterface
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return Service::class;
    }
}
