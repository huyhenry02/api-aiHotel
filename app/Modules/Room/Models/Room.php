<?php

namespace App\Modules\Room\Models;

use App\Models\BaseModel;
use App\Modules\Hotel\Models\Hotel;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Review\Models\Review;
use App\Modules\RoomType\Models\RoomType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends BaseModel
{
    public $table = 'rooms';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'code',
        'floor',
        'room_type_id',
        'hotel_id',
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

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'room_id');
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'hotel_id');
    }
}
