<?php

namespace App\Modules\Hotel\Models;

use App\Models\BaseModel;
use App\Modules\File\Models\File;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends BaseModel
{
    use SoftDeletes;

    public $table = 'hotels';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'address',
        'description',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'file_model');
    }
}
