<?php

namespace App\Modules\Review\Repositories;

use App\Modules\Example\Repositories\Interfaces\ExampleInterface;
use App\Modules\Review\Models\Response_Review;
use App\Modules\Review\Models\Review;
use App\Repositories\BaseRepository;


class ResponseReviewRepository extends BaseRepository implements ExampleInterface
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return Response_Review::class;
    }
}
