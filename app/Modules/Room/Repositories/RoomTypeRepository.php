<?php

namespace App\Modules\Room\Repositories;

use App\Modules\Hotel\Models\Hotel;
use App\Modules\Room\Models\RoomType;
use App\Modules\Room\Repositories\Interfaces\RoomTypeInterface;
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
    public function getLastRoomOnFloor($floorNumber)
    {
        $lastRoom = $this->model
            ->where('code', 'LIKE', $floorNumber . '%')
            ->orderBy('code', 'desc')
            ->first();
        return $lastRoom;
    }
}
