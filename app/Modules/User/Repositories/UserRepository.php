<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\Models\User;
use App\Modules\User\Repositories\Interfaces\UserInterface;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserInterface
{

    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return User::class;
    }

}
