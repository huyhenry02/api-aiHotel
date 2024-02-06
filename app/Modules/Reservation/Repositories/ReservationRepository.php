<?php

namespace App\Modules\Reservation\Repositories;

use App\Modules\Reservation\Models\Reservation;
use App\Modules\Reservation\Repositories\Interfaces\ReservationInterface;
use App\Modules\Room\Models\Room;
use App\Repositories\BaseRepository;


class ReservationRepository extends BaseRepository implements ReservationInterface
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return Reservation::class;
    }
}
