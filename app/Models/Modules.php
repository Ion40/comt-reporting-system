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

    // Relación para obtener el nombre del padre
    public function parent()
    {
        return $this->belongsTo(Modules::class, 'parent_id');
    }

// Relación opcional para contar hijos si lo necesitas después
    public function children()
    {
        return $this->hasMany(Modules::class, 'parent_id');
    }

    public function reportIframe()
    {
        // Relación 1 a 1: Un módulo puede tener un registro de Iframe
        return $this->hasOne(ReportIframe::class, 'module_id');
    }
}
