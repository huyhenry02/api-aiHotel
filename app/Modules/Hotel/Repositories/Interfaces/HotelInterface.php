<?php

namespace App\Modules\Hotel\Repositories\Interfaces;

use App\Repositories\Interfaces\RepositoryInterface;

interface HotelInterface extends RepositoryInterface
{
    public function findWithBanner($id);
}
