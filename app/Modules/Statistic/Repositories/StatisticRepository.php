<?php

namespace App\Modules\Statistic\Repositories;

use App\Modules\Example\Models\Example;
use App\Modules\Statistic\Repositories\Interfaces\StatisticInterface;
use App\Modules\User\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;


class StatisticRepository extends BaseRepository implements StatisticInterface
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return Example::class;
    }

    /**
     * @param Model $model
     * @param $startDate
     * @param string $type
     * @return mixed
     */
    public function getDataStatistics(Model $model, $startDate, string $type): mixed
    {

        $query = $model->where('created_at', '>=', $startDate);
        if ($model instanceof User) {
            $query = $model->where('role_type', 'customer');
        }
        switch ($type) {
            case 'week':
                $query->selectRaw('CAST(EXTRACT(WEEK FROM created_at) AS INTEGER) as week, COUNT(*) as count')
                    ->whereRaw('EXTRACT(MONTH FROM created_at) = EXTRACT(MONTH FROM TO_TIMESTAMP(?, \'YYYY-MM-DD\'))', [$startDate])
                    ->groupBy('week');
                break;
            case 'month':
                $query->selectRaw('CAST(EXTRACT(MONTH FROM created_at) AS INTEGER) as month, COUNT(*) as count')
                    ->whereRaw('EXTRACT(YEAR FROM created_at) = EXTRACT(YEAR FROM TO_TIMESTAMP(?, \'YYYY-MM-DD\'))', [$startDate])
                    ->groupBy('month');
                break;
            case 'year':
                $query->selectRaw('CAST(EXTRACT(YEAR FROM created_at) AS INTEGER) as year, COUNT(*) as count')
                    ->whereRaw('EXTRACT(YEAR FROM created_at) >= EXTRACT(YEAR FROM TO_TIMESTAMP(?, \'YYYY-MM-DD\'))', [$startDate])
                    ->groupBy('year');
                break;
            case 'day':
                $query->selectRaw('CAST(EXTRACT(DAY FROM created_at) AS INTEGER) as day, COUNT(*) as count')
                    ->whereRaw('EXTRACT(WEEK FROM created_at) = EXTRACT(WEEK FROM TO_TIMESTAMP(?, \'YYYY-MM-DD\'))', [$startDate])
                    ->groupBy('day');
                break;
        }
        return $query->get();
    }
}
