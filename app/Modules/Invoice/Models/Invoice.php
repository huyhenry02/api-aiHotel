<?php

namespace App\Modules\Invoice\Models;

use App\Models\BaseModel;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Service\Models\Service;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends BaseModel
{
    use SoftDeletes;

    public $table = 'invoices';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'total',
        'status',
        'payment_method',
        'total_day',
        'code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'invoice_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'invoice_has_service', 'invoice_id');
    }
}
