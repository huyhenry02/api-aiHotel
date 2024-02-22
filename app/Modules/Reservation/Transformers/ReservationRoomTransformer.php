<?php

namespace App\Modules\Reservation\Transformers;

use App\Modules\Reservation\Models\Reservation;
use League\Fractal\TransformerAbstract;

class ReservationRoomTransformer extends TransformerAbstract
{
    public function transform(Reservation $reservation): array
    {
        return [
            'id' => $reservation->id,
            'code' => $reservation->code,
            'start_date' => $reservation->start_date,
            'end_date' => $reservation->end_date,
            'status' => $reservation->status,
        ];
    }

}
