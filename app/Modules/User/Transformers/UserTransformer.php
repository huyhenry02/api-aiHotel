<?php

namespace App\Modules\User\Transformers;

use App\Modules\User\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
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
}
