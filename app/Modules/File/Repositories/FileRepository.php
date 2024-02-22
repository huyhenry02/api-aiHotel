<?php

namespace App\Modules\File\Repositories;

use App\Modules\File\Repositories\Interfaces\FileInterface;
use App\Modules\File\Models\File;
use App\Repositories\BaseRepository;
use Illuminate\Http\UploadedFile;


class FileRepository extends BaseRepository implements FileInterface
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return File::class;
    }
    public function uploadFile(UploadedFile $file, string $modelType, int $modelId, string $collection): string
    {
        $modelInstance = new $modelType;
        $tableName = $modelInstance->getTable();
        $fileName = $collection . '_' . $tableName . '_' . $modelId;
        $filePath = $file->storeAs('uploads', $fileName, 'public');
        $file = new File;
        $file->path = $filePath;
        $file->file_model_id = $modelId;
        $file->file_model_type = $modelType;
        $file->model_collection = $collection;
        $file->original_url = asset('storage/' . $filePath);
        $file->save();
        return asset('storage/' . $filePath);
    }

    /**
     * @param string $modelType
     * @param int $modelId
     * @return mixed
     */
    public function findWithFile(string $modelType, int $modelId): mixed
    {
        $modelInstance = new $modelType;
        return $modelInstance::with('files')->find($modelId);
    }
}
