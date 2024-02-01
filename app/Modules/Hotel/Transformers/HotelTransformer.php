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
            'banner' => $hotel->banner ?? '',
            'description' => $hotel->description ?? '',
        ];
    }
}
