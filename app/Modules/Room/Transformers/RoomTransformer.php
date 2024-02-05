<?php

namespace App\Modules\Room\Transformers;

use App\Modules\Hotel\Models\Hotel;
use App\Modules\Room\Models\Room;
use League\Fractal\TransformerAbstract;

class RoomTransformer extends TransformerAbstract
{
    public function transform(Room $room): array
    {
        return [
            'id' => $room->id,
            'code' => $room->code ?? '',
            'floor' => $room->floor ?? '',
            'hotel' => [
                'id' => $room->hotel->id,
                'name' => $room->hotel->name,
                'address' => $room->hotel->address,
                'description' => $room->hotel->description,
            ],
            'room_type' => [
                'id' => $room->roomType->id,
                'name' => $room->roomType->name,
                'description' => $room->roomType->description,
                'price' => $room->roomType->price,
            ],
        ];
    }

}
