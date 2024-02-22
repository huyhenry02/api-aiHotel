<?php

namespace App\Modules\File\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends BaseModel
{
    public $table = 'files';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = ['path', 'file_model_id', 'file_model_type','model_collection','original_url'];

    public function file_model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
