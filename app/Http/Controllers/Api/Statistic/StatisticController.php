<?php

namespace App\Http\Controllers\Api\Statistic;

use App\Http\Controllers\ApiController;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Statistic\Repositories\Interfaces\StatisticInterface;
use App\Modules\Statistic\Requests\StatisticRequest;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;


class StatisticController extends ApiController
{
    protected StatisticInterface $statisticRepo;

    public function __construct(StatisticInterface $statisticRepo)
    {
        $this->statisticRepo = $statisticRepo;
    }

    public function statistic(StatisticRequest $request): JsonResponse
    {
        $postData = $request->validated();
        $collectionModelMap = [
            'customer' => User::class,
            'reservation' => Reservation::class,
        ];
        $modelClass = $collectionModelMap[$postData['collection']] ?? null;
        if (!$modelClass) {
            return $this->respondError(__('messages.not_found'));
        }
        $model = new $modelClass;
        $data = $this->statisticRepo->getDataStatistics(model: $model,startDate: $postData['start_date'], type: $postData['type']);
        return $this->respondSuccess($data);
    }
}
