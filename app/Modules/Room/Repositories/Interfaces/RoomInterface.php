<?php

namespace App\Modules\Room\Repositories\Interfaces;

use App\Repositories\Interfaces\RepositoryInterface;

interface RoomInterface extends RepositoryInterface
{
    /**
     * @param  $floorNumber
     * @param  $hotelId
     * @return mixed
     */
    public function getLastRoomOnFloor($floorNumber, $hotelId): mixed;

    /**
     * @param $lastRoom
     * @param $floorNumber
     * @param $room
     * @return void
     */
    public function generateCodeRoom($lastRoom, $floorNumber, $room): void;
}
