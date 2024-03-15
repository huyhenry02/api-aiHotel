<?php

namespace App\Http\Controllers\Api\Review;

use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ReviewController extends ApiController
{
    use AuthorizesRequests, ValidatesRequests;
}
