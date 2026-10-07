<?php

namespace Modules\Base\Models;

use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    protected static $siteConfigCache;

    public $timestamps = false;

    protected $table = 'site_configs';

    protected $fillable = ['key', 'value'];

    /**
     * Get the value of a site config by key, with an optional default.
     */
    public static function get(string $key, ?string $default = null)
    {
        self::loadSiteConfigCache();

        return self::$siteConfigCache[$key]->value ?? $default ?? false;
    }

    /**
     * Fetch all site configs from the database if not already cached.
     */
    protected static function loadSiteConfigCache()
    {
        if (is_null(self::$siteConfigCache)) {
            self::$siteConfigCache = self::all()->keyBy('key');
        }
    }

    /**
     * Set the value of a site config by key.
     */
    public static function set(string $key, ?string $value): bool
    {
        if ($value === null) {
            return false;
        }

        self::loadSiteConfigCache();

        if (! isset(self::$siteConfigCache[$key])) {
            $model = self::create(['key' => $key, 'value' => $value]);
            self::$siteConfigCache[$key] = $model;
        } else {
            $model = self::$siteConfigCache[$key];
            $model->update(['value' => $value]);
        }

        return true;
    }
}
