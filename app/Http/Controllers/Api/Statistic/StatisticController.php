<?php

namespace App\Http\Controllers\Api\Statistic;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\StatisticRequest;
use App\Modules\Reservation\Repositories\Interfaces\ReservationInterface;
use App\Modules\User\Repositories\Interfaces\UserInterface;
use Illuminate\Http\JsonResponse;


class StatisticController extends ApiController
{
    protected ReservationInterface $reservationRepo;
    protected UserInterface $userRepo;

    public function __construct(ReservationInterface $reservationRepo, UserInterface $userRepo)
    {
        $this->reservationRepo = $reservationRepo;
        $this->userRepo = $userRepo;
    }

    public function statisticCustomer(StatisticRequest $request): JsonResponse
    {
        $postData = $request->validated();
        $data = $this->userRepo->getCustomerStatistics(startDate: $postData['start_date'], endDate: $postData['end_date'], type: $postData['type']);
        return $this->respondSuccess($data);
    }

    public function statisticReservation(StatisticRequest $request): JsonResponse
    {
        $postData = $request->validated();
        $startDate = $postData['start_date'];
        $endDate = $postData['end_date'];
        $data = $this->reservationRepo->getReservationStatistics($startDate, $endDate);
        return $this->respondSuccess($data);
    }
}
