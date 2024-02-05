<?php

namespace App\Modules\Room\Transformers;

use App\Modules\Hotel\Models\Hotel;
use App\Modules\Room\Models\RoomType;
use League\Fractal\TransformerAbstract;

class RoomTypeTransformer extends TransformerAbstract
{
    public function transform(RoomType $roomType): array
    {
        return [
            'id' => $roomType->id,
            'name' => $roomType->name ?? '',
            'code' => $roomType->code ?? '',
            'price' => $roomType->price ?? '',
            'description' => $roomType->description ?? '',
        ];
    }
}
