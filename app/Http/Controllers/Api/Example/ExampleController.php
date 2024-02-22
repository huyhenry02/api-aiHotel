<?php

namespace App\Http\Controllers\Api\Example;

use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ExampleController extends ApiController
{
    use AuthorizesRequests, ValidatesRequests;
}
