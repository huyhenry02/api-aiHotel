<?php

namespace App\Modules\Review\Repositories;

use App\Modules\Review\Models\ResponseReview;
use App\Modules\Review\Repositories\Interfaces\ResponseReviewInterface;
use App\Repositories\BaseRepository;


class ResponseReviewRepository extends BaseRepository implements ResponseReviewInterface
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return ResponseReview::class;
    }

    /**
     * @param int $reviewId
     * @return mixed
     */
    public function getListResponseInReview(int $reviewId): mixed
    {
        return $this->_model->where('review_id', $reviewId)->get();
    }
}
