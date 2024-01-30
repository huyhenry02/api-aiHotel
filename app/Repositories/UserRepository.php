<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserInterface;

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
