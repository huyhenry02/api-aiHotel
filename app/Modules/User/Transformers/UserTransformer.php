<?php

namespace App\Modules\User\Transformers;

use App\Modules\File\Transformers\FileTransformer;
use App\Modules\User\Models\User;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'files'
    ];

    public function transform(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name ?? '',
            'role_type' => $user->role_type ?? '',
            'address' => $user->address ?? '',
            'phone' => $user->phone ?? '',
            'identification' => $user->identification ?? '',
            'email' => $user->email ?? '',
            'age' => $user->age ?? '',
        ];
    }

    public function includeFiles(User $user): ?Collection
    {
        if ($user->files) {
            return $this->collection($user->files, new FileTransformer());
        }
        return null;
    }
}
