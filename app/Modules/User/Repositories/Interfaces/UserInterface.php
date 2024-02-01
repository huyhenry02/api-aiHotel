<?php

namespace App\Modules\User\Repositories\Interfaces;

use App\Modules\User\Models\User;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;

interface UserInterface extends RepositoryInterface
{
}
