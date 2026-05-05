<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrafficEvent extends Model
{
    protected $table = 'traffic_events';
    
    public $timestamps = false; // We use a custom created_at column in schema

    protected $fillable = [
        'traffic_id',
        'session_id',
        'event_type',
        'element_tag',
        'element_id',
        'element_text',
        'page_path',
    ];

    public function traffic()
    {
        return $this->belongsTo(TrafficAnalytics::class, 'traffic_id');
    }
}
