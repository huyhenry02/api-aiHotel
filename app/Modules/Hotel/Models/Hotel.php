<?php

namespace App\Modules\Hotel\Models;

use App\Models\BaseModel;
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
    public function roomTypes(): BelongsToMany
    {
        return $this->belongsToMany(RoomType::class,'hotel_has_room_types', 'hotel_id', 'room_type_id');
    }
}
