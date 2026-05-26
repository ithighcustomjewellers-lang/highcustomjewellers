<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsTracking extends Model
{
    protected $table = 'analytics_tracking';
    protected $fillable = ['profile_slug', 'event_type', 'platform', 'ip_address', 'user_agent'];

    public static function trackQRScan($profileSlug)
    {
        return self::create([
            'profile_slug' => $profileSlug,
            'event_type' => 'qr_scan',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public static function trackButtonClick($profileSlug, $platform)
    {
        return self::create([
            'profile_slug' => $profileSlug,
            'event_type' => 'button_click',
            'platform' => $platform,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public static function getQRScansCount($profileSlug, $days = 30)
    {
        return self::where('profile_slug', $profileSlug)
            ->where('event_type', 'qr_scan')
            ->where('created_at', '>=', now()->subDays($days))
            ->count();
    }

    public static function getButtonClicksCount($profileSlug, $days = 30)
    {
        return self::where('profile_slug', $profileSlug)
            ->where('event_type', 'button_click')
            ->where('created_at', '>=', now()->subDays($days))
            ->count();
    }
}
