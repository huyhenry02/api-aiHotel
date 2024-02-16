<?php

namespace App\Modules\Reservation\Repositories\Interfaces;

use App\Repositories\Interfaces\RepositoryInterface;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReservationInterface extends RepositoryInterface
{
    /**
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function filterReservation(array $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return mixed
     */
    public function getReservationStatistics(DateTime $startDate, DateTime $endDate): mixed;

}
