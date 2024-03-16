<?php

namespace App\Modules\Review\Repositories\Interfaces;

use App\Repositories\Interfaces\RepositoryInterface;

interface ResponseReviewInterface extends RepositoryInterface
{
    /**
     * @param int $reviewId
     * @return mixed
     */
    public function getListResponseInReview(int $reviewId): mixed;
}
