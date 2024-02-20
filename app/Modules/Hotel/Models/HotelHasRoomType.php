<?php

namespace App\Modules\Hotel\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HotelHasRoomType extends BaseModel
{

    public $table = 'hotel_has_room_types';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'hotel_id',
        'room_type_id',
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
    public function hotel(): belongsToMany
    {
        return $this->belongsToMany(Hotel::class);
    }
}
