<?php

namespace App\Modules\Hotel\Repositories;

use App\Modules\Hotel\Models\Hotel;
use App\Modules\Hotel\Repositories\Interfaces\HotelInterface;
use App\Repositories\BaseRepository;


class HotelRepository extends BaseRepository implements HotelInterface
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return Hotel::class;
    }
}
