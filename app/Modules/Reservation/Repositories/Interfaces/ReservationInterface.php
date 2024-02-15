<?php

namespace App\Modules\Reservation\Repositories\Interfaces;

use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReservationInterface extends RepositoryInterface
{
    public function filterReservation(array $filters, int $perPage = 15): LengthAwarePaginator;

}
