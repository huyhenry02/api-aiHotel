<?php

namespace App\Modules\User\Models;

use App\Modules\File\Models\File;
use App\Modules\Invoice\Models\Invoice;
use App\Modules\Reservation\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Auditable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;
    use SoftDeletes;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role_type',
        'address',
        'phone',
        'email',
        'password',
        'identification',
        'age',
    ];
    /**
     * The primary key for the model.
     *
     * @var int
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }
    public function invoicesPaid(): HasMany
    {
        return $this->hasMany(Invoice::class, 'user_id_paid');
    }
    public function invoicesCheckIn(): HasMany
    {
        return $this->hasMany(Invoice::class, 'user_id_check_in');
    }
    public function invoicesCheckOut(): HasMany
    {
        return $this->hasMany(Invoice::class, 'user_id_check_out');
    }
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'file_model');
    }
}
