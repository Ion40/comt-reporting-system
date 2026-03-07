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

    public function reportIframe()
    {
        // Relación 1 a 1: Un módulo puede tener un registro de Iframe
        return $this->hasOne(ReportIframe::class, 'module_id');
    }
}
