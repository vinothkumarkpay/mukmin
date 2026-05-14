<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /** @var array<string, mixed> */
    private static $runtimeCache = [];

    public static function get(string $key, $default = null)
    {
        if (! array_key_exists($key, self::$runtimeCache)) {
            self::$runtimeCache[$key] = static::query()->where('key', $key)->value('value');
        }

        return self::$runtimeCache[$key] ?? $default;
    }

    public static function set(string $key, $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        self::$runtimeCache[$key] = $value;
    }

    public static function forgetRuntimeCache(): void
    {
        self::$runtimeCache = [];
    }
}
