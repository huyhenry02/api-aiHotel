<?php

namespace App\Modules\Room\Repositories\Interfaces;

use App\Repositories\Interfaces\RepositoryInterface;

interface RoomInterface extends RepositoryInterface
{
    public function getLastRoomOnFloor($floorNumber);
}
