<?php

namespace App\Modules\Room\Repositories\Interfaces;

use App\Repositories\Interfaces\RepositoryInterface;

interface RoomInterface extends RepositoryInterface
{
    /**
     * @param int $floorNumber
     * @param int $hotelId
     * @return mixed
     */
    public function getLastRoomOnFloor(int $floorNumber,int $hotelId): mixed;

    /**
     * @param int $lastRoom
     * @param int $floorNumber
     * @param int $room
     * @return void
     */
    public function generateCodeRoom(int $lastRoom,int $floorNumber,int $room): void;
}
