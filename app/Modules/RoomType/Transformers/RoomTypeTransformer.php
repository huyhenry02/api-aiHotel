<?php

namespace App\Modules\RoomType\Transformers;

use App\Modules\RoomType\Models\RoomType;
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
