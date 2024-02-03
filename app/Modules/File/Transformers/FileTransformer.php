<?php

namespace App\Modules\File\Transformers;

use App\Modules\File\Models\File;
use League\Fractal\TransformerAbstract;

class FileTransformer extends TransformerAbstract
{
    public function transform(File $file): array
    {
        return [
            'id' => $file->id,
            'path' => $file->path ?? '',
            'file_model_type' => $file->file_model_type ?? '',
            'file_model_id' => $file->file_model_id ?? '',
            'model_collection' => $file->model_collection ?? '',
            'original_url' => $file->original_url ?? '',
        ];
    }
}
