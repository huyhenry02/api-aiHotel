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
            'room_type' => $room->description ?? '',
        ];
    }
}
