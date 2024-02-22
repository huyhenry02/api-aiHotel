<?php

namespace App\Modules\Example\Transformers;

use App\Modules\Example\Models\Example;
use League\Fractal\TransformerAbstract;

class ExampleTransformer extends TransformerAbstract
{
    public function transform(Example $example): array
    {
        return [
            //
        ];
    }
}
