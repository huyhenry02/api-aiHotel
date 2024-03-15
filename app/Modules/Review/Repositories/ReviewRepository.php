<?php

namespace App\Modules\Review\Repositories;

use App\Modules\Example\Repositories\Interfaces\ExampleInterface;
use App\Modules\Review\Models\Review;
use App\Repositories\BaseRepository;


class ReviewRepository extends BaseRepository implements ExampleInterface
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
}
