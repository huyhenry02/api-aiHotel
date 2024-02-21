<?php

namespace App\Modules\Room\Repositories;

use App\Enums\ReservationStatusEnum;
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
     * @param  $floorNumber
     * @param  $hotelId
     * @return mixed
     */
    public function getLastRoomOnFloor($floorNumber, $hotelId): mixed
    {
        return $this->_model
            ->where('hotel_id', $hotelId)
            ->where('code', 'LIKE', $floorNumber . '%')
            ->orderBy('code', 'desc')
            ->first();
    }

    /**
     * @param  $lastRoom
     * @param  $floorNumber
     * @param  $room
     * @return void
     */
    public function generateCodeRoom($lastRoom, $floorNumber, $room): void
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

    /**
     * @param $roomId
     * @return mixed
     */
    public function getListCurrentReservationInRoom($roomId): mixed
    {
        $room = $this->_model->find($roomId);
        return $room->reservations()
            ->where('end_date', '>=', now())
            ->whereIn('status', [ReservationStatusEnum::PROCESSING, ReservationStatusEnum::PENDING])
            ->get();
    }
}
