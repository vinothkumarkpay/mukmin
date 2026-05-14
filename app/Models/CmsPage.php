<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'body',
        'widgets_only',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'widgets_only' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * In-page widgets (zone "cms") shown below the page body on /page/{slug}.
     */
    public function inPageWidgets(): HasMany
    {
        return $this->hasMany(Widget::class, 'cms_page_id')
            ->where('zone', 'cms')
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
