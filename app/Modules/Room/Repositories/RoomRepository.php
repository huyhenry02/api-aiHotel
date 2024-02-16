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

    /**
     * @param int $floorNumber
     * @param int $hotelId
     * @return mixed
     */
    public function getLastRoomOnFloor(int $floorNumber,int $hotelId): mixed
    {
        return $this->_model
            ->where('hotel_id', $hotelId)
            ->where('code', 'LIKE', $floorNumber . '%')
            ->orderBy('code', 'desc')
            ->first();
    }

    /**
     * @param int $lastRoom
     * @param int $floorNumber
     * @param int $room
     * @return void
     */
    public function generateCodeRoom(int $lastRoom,int $floorNumber,int $room): void
    {
        if (!$lastRoom) {
            $room->code = $floorNumber . '01';
        } else {
            $lastRoomCode = $lastRoom->code;
            $lastRoomNumber = (int)substr($lastRoomCode, -2);
            $newRoomNumber = $lastRoomNumber + 1;
            $newRoomNumber = str_pad($newRoomNumber, 2, '0', STR_PAD_LEFT);
            $room->code = $floorNumber . $newRoomNumber;
        }
    }
}
