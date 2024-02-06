<?php

namespace App\Modules\Reservation\Models;

use App\Models\BaseModel;
use App\Modules\Room\Models\Room;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends BaseModel
{
    use SoftDeletes;

    public $table = 'reservations';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'code',
        'user_id',
        'start_date',
        'end_date',
        'check_in',
        'check_out',
        'status',
        'reject_reason',
        'room_id',
        'amount_person',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class,'room_id');
    }
}
