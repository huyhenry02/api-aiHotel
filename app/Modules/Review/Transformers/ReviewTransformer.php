<?php

namespace App\Modules\Review\Transformers;

use App\Modules\Example\Models\Example;
use League\Fractal\TransformerAbstract;

class ReviewTransformer extends TransformerAbstract
{
    public function transform(Example $example): array
    {
        return [
            //
        ];
    }
}
