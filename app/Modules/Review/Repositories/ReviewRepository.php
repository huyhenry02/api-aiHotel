<?php

namespace App\Modules\Review\Repositories;

use App\Modules\Example\Repositories\Interfaces\ExampleInterface;
use App\Modules\Review\Models\Review;
use App\Modules\Review\Repositories\Interfaces\ReviewInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;


class ReviewRepository extends BaseRepository implements ReviewInterface
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return Review::class;
    }

    /**
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function filterReviews(array $filters,  int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->_model->query();
        if (isset($filters['hotel_id'])) {
            $query->where('hotel_id', $filters['hotel_id']);
        }
        if (isset($filters['start_date'])) {
            $query->where(DB::raw('DATE(created_at)'), '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where(DB::raw('DATE(created_at)'), '<=', $filters['end_date']);
        }
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween(DB::raw('DATE(created_at)'), [$filters['start_date'], $filters['end_date']]);
        }
        return $query->paginate($perPage);
    }
}
