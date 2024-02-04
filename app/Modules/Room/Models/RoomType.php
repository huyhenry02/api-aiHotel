<?php

namespace App\Modules\Room\Models;

use App\Models\BaseModel;
use App\Modules\Hotel\Models\Hotel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends BaseModel
{
    public $table = 'room_types';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'code',
        'description',
        'price',
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

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'room_type_id');
    }
    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(Hotel::class,'hotel_has_room_types', 'hotel_id', 'room_type_id');
    }


}
