<?php

namespace Modules\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AdminConfig extends Model
{
    protected static $adminConfigCache;

    protected $table = 'admin_configs';

    protected $fillable = ['key', 'value', 'group'];

    /**
     * Check if the table exists.
     */
    protected static function tableExists(): bool
    {
        try {
            return Schema::hasTable('admin_configs');
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Get the value of an admin config by key, with an optional default.
     */
    public static function get(string $key, ?string $default = null, string $group = null): mixed
    {
        if (! self::tableExists()) {
            return $default ?? false;
        }

        self::loadAdminConfigCache();

        if ($group) {
            $cacheKey = "{$group}.{$key}";
            return self::$adminConfigCache[$cacheKey]->value ?? $default ?? false;
        }

        return self::$adminConfigCache[$key]->value ?? $default ?? false;
    }

    /**
     * Get config value by group and key.
     */
    public static function getByGroup(string $group, string $key, ?string $default = null): mixed
    {
        if (! self::tableExists()) {
            return $default ?? false;
        }

        self::loadAdminConfigCache();
        $cacheKey = "{$group}.{$key}";

        return self::$adminConfigCache[$cacheKey]->value ?? $default ?? false;
    }

    /**
     * Get all configs for a group.
     */
    public static function getGroup(string $group): array
    {
        if (! self::tableExists()) {
            return [];
        }

        self::loadAdminConfigCache();

        return self::$adminConfigCache
            ->filter(fn ($config) => str_starts_with($config->key, "{$group}."))
            ->mapWithKeys(fn ($config) => [
                str_replace("{$group}.", '', $config->key) => $config->value,
            ])
            ->toArray();
    }

    /**
     * Fetch all admin configs from the database if not already cached.
     */
    protected static function loadAdminConfigCache(): void
    {
        if (is_null(self::$adminConfigCache) && self::tableExists()) {
            self::$adminConfigCache = Cache::remember('admin_configs_cache', now()->addHours(6), function () {
                return self::all()->keyBy(function ($config) {
                    return $config->group ? "{$config->group}.{$config->key}" : $config->key;
                });
            });
        }
    }

    /**
     * Set the value of an admin config by key.
     */
    public static function set(string $key, ?string $value, string $group = 'general'): bool
    {
        if ($value === null) {
            return false;
        }

        if (! self::tableExists()) {
            return false;
        }

        self::loadAdminConfigCache();

        $cacheKey = $group ? "{$group}.{$key}" : $key;

        if (! isset(self::$adminConfigCache[$cacheKey])) {
            $model = self::create(['key' => $key, 'value' => $value, 'group' => $group]);
            self::$adminConfigCache[$cacheKey] = $model;
        } else {
            $model = self::$adminConfigCache[$cacheKey];
            $model->update(['value' => $value]);
        }

        Cache::forget('admin_configs');

        return true;
    }

    /**
     * Set multiple configs for a group.
     */
    public static function setGroup(string $group, array $configs): void
    {
        if (! self::tableExists()) {
            return;
        }

        foreach ($configs as $key => $value) {
            self::set($key, (string) $value, $group);
        }
    }

    /**
     * Clear the cache.
     */
    public static function clearCache(): void
    {
        self::$adminConfigCache = null;
        Cache::forget('admin_configs');
        Cache::forget('admin_configs_cache');
    }
}