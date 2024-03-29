<?php

namespace App\Modules\Review\Models;

use App\Models\BaseModel;
use App\Modules\Hotel\Models\Hotel;
use App\Modules\Room\Models\Room;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends BaseModel
{

    public $table = 'reviews';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'user_id',
        'room_id',
        'content',
        'rating',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
    public function responseReview(): HasMany
    {
        return $this->hasMany(ResponseReview::class, 'review_id');
    }
}
