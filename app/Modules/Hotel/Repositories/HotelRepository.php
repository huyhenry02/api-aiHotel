<?php

namespace App\Modules\Hotel\Repositories;

use App\Modules\Hotel\Models\Hotel;
use App\Modules\Hotel\Repositories\Interfaces\HotelInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


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
