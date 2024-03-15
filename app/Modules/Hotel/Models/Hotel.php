<?php

namespace App\Modules\Hotel\Models;

use App\Models\BaseModel;
use App\Modules\File\Models\File;
use App\Modules\Review\Models\Review;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\RoomType\Models\RoomType;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hotel extends BaseModel
{
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
    public function roomTypes(): BelongsToMany
    {
        return $this->belongsToMany(RoomType::class,'hotel_has_room_types', 'hotel_id', 'room_type_id');
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'hotel_id');
    }
}
