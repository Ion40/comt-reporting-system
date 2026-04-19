<?php

namespace App\Models;

use App\Events\PermisosActualizados;
use Illuminate\Database\Eloquent\Model;

class PermissionUser extends Model
{
    protected $table = 'permission_user';

    protected $fillable = [
        'module_id',
        'user_id',
        'permission_id'
    ];

    protected $dispatchesEvents = [
        'saved' => PermisosActualizados::class,
        'deleted' => PermisosActualizados::class,
    ];

}
