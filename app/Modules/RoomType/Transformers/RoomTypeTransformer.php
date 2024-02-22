<?php

namespace App\Modules\RoomType\Transformers;

use App\Modules\File\Transformers\FileTransformer;
use App\Modules\RoomType\Models\RoomType;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class RoomTypeTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'files'
    ];
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
    public function includeFiles(RoomType $roomType): ?Collection
    {
        if ($roomType->files) {
            return $this->collection($roomType->files, new FileTransformer());
        }
        return null;
    }
}
