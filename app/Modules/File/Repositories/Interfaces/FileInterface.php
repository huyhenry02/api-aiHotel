<?php

namespace App\Modules\File\Repositories\Interfaces;

use Illuminate\Http\UploadedFile;

interface FileInterface
{
    public function uploadFile(UploadedFile $file, string $modelType, int $modelId, string $collection): string;
}
