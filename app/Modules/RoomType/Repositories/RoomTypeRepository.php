<?php

namespace App\Modules\RoomType\Repositories;

use App\Modules\RoomType\Models\RoomType;
use App\Modules\RoomType\Repositories\Interfaces\RoomTypeInterface;
use App\Repositories\BaseRepository;


class RoomTypeRepository extends BaseRepository implements RoomTypeInterface
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return RoomType::class;
    }
}
