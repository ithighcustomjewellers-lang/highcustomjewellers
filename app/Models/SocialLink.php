<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $fillable = ['user_id', 'platform_name', 'platform_url', 'icon_class', 'sort_order', 'is_active'];

   protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method to check if should use custom image
    public function useCustomIcon()
    {
        return $this->icon_type === 'custom';
    }
}
