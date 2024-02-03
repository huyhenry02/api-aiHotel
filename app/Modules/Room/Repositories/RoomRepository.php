<?php

namespace App\Modules\Room\Repositories;

use App\Modules\Room\Repositories\Interfaces\RoomInterface;
use App\Modules\Room\Models\Room;
use App\Repositories\BaseRepository;


class RoomRepository extends BaseRepository implements RoomInterface
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return Room::class;
    }
}
