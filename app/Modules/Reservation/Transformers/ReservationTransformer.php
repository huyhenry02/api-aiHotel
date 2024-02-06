<?php

namespace App\Modules\Reservation\Transformers;

use App\Modules\Reservation\Models\Reservation;
use League\Fractal\TransformerAbstract;

class ReservationTransformer extends TransformerAbstract
{
    public function transform(Reservation $reservation): array
    {
        return [
            'id' => $reservation->id,
            'code' => $reservation->code,
            'user' => [
                'id' => $reservation->user->id,
                'name' => $reservation->user->name,
                'email' => $reservation->user->email,
                'phone' => $reservation->user->phone,
            ],
            'hotel' => [
                'id' => $reservation->room->hotel->id,
                'name' => $reservation->room->hotel->name,
                'address' => $reservation->room->hotel->address,
                'description' => $reservation->room->hotel->description,
            ],
            'room_type' => [
                'id' => $reservation->room->roomType->id,
                'name' => $reservation->room->roomType->name,
                'description' => $reservation->room->roomType->description,
                'price' => $reservation->room->roomType->price,
            ],
            'room' => [
                'id' => $reservation->room->id,
                'code' => $reservation->room->code,
                'floor' => $reservation->room->floor,
            ],
            'start_date' => $reservation->start_date,
            'end_date' => $reservation->end_date,
            'check_in' => $reservation->check_in,
            'check_out' => $reservation->check_out,
            'amount_person' => $reservation->amount_person,
            'status' => $reservation->status,
            'reject_reason' => $reservation->reject_reason,
        ];
    }
}
