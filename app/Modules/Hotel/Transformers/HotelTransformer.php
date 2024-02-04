<?php

namespace App\Modules\Hotel\Transformers;

use App\Modules\Hotel\Models\Hotel;
use League\Fractal\TransformerAbstract;

class HotelTransformer extends TransformerAbstract
{
    public function transform(Hotel $hotel): array
    {
        return [
            'id' => $hotel->id,
            'name' => $hotel->name ?? '',
            'address' => $hotel->address ?? '',
            'room_types' => $hotel->roomTypes->map(function ($roomType) {
                return [
                    'id' => $roomType->id,
                    'name' => $roomType->name,
                    'code' => $roomType->code,
                    'description' => $roomType->description,
                    'price' => $roomType->price,
                ];
            }),
            'description' => $hotel->description ?? '',
        ];
    }
}