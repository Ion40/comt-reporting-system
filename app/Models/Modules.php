<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
    protected $table = 'modules';

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'icon_class',
        'url_path',
        'show_menu',
        'order_menu'
    ];
}
