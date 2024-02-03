<?php

namespace App\Modules\Hotel\Transformers;

use App\Modules\File\Transformers\FileTransformer;
use App\Modules\Hotel\Models\Hotel;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class HotelTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'files'
    ];
    public function transform(Hotel $hotel): array
    {
        return [
            'id' => $hotel->id,
            'name' => $hotel->name ?? '',
            'address' => $hotel->address ?? '',
            'description' => $hotel->description ?? '',
        ];
    }
    public function includeFiles(Hotel $hotel): Collection
    {
        return $this->collection($hotel->files, new FileTransformer());
    }
}
