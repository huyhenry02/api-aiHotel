<?php

namespace App\Modules\File\Repositories\Interfaces;

use Illuminate\Http\UploadedFile;

interface FileInterface
{
    /**
     * @param UploadedFile $file
     * @param string $modelType
     * @param int $modelId
     * @param string $collection
     * @return string
     */
    public function uploadFile(UploadedFile $file, string $modelType, int $modelId, string $collection): string;

    /**
     * @param string $modelType
     * @param int $modelId
     * @return mixed
     */
    public function findWithFile(string $modelType, int $modelId): mixed;
}
