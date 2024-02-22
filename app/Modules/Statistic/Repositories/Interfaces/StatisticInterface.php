<?php

namespace App\Modules\Statistic\Repositories\Interfaces;

use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

interface StatisticInterface extends RepositoryInterface
{
    /**
     * @param Model $model
     * @param $startDate
     * @param string $type
     * @return mixed
     */
    public function getDataStatistics(Model $model, $startDate, string $type): mixed;
}
