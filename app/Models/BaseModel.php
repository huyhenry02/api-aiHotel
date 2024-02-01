<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BaseModel extends Model implements Auditable
{
    //auto write log add/update/edit
    use \OwenIt\Auditing\Auditable;
    /**
     * The primary key for the model.
     *
     * @var int
     */
    protected $primaryKey = 'id';
    /**
     * @return void
     */
}
