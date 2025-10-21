<?php

namespace App\Events;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SiteSettingCache
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct()
    {
        Cache::forget('settings');
        Cache::rememberForever('settings', function () {
            return SiteSetting::first();
        });
    }
}
