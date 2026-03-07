<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportIframe extends Model
{
    protected $table = 'report_iframes';

    protected $fillable = ['module_id', 'title', 'iframe_url', 'is_active'];

    public function module()
    {
        return $this->belongsTo(Modules::class, 'module_id');
    }
}
