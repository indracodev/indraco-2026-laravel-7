<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrafficAnalytics extends Model
{
    protected $table = 'traffic_analytics';

    protected $fillable = [
        'url',
        'path',
        'method',
        'status_code',
        'response_time',
        'response_size',
        'ip_address',
        'country_code',
        'user_agent',
        'os',
        'browser',
        'device_type',
        'scroll_depth',
        'referer',
        'user_id',
        'session_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
