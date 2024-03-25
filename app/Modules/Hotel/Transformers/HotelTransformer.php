<?php

namespace App\Modules\Hotel\Transformers;

use App\Modules\File\Transformers\FileTransformer;
use App\Modules\Hotel\Models\Hotel;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class HotelTransformer extends TransformerAbstract
{
//    protected array $availableIncludes = [
//        'files'
//    ];

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

//    public function includeFiles(Hotel $hotel): ?Collection
//    {
//        if ($hotel->files) {
//            return $this->collection($hotel->files, new FileTransformer());
//        }
//        return null;
//    }
}
