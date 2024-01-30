<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Repositories\Interfaces\UserInterface;
use JetBrains\PhpStorm\NoReturn;

class UserController extends ApiController
{
    protected UserInterface $userRepo;
    public function __construct(UserInterface $user)
    {
        $this->userRepo = $user;

    }
    #[NoReturn] public function test(): void
    {
        dd($this->userRepo);
    }
}
