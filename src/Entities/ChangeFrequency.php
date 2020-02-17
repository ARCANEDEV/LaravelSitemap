<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSitemap\Entities;

use Arcanedev\LaravelSitemap\Contracts\Entities\ChangeFrequency as ChangeFrequencyContract;
use Illuminate\Support\Collection;

/**
 * Class     ChangeFrequency
 *
 * @package  Arcanedev\LaravelSitemap\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ChangeFrequency implements ChangeFrequencyContract
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get all the valid frequency keys.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function keys(): Collection
    {
        return new Collection([
            static::ALWAYS,
            static::HOURLY,
            static::DAILY,
            static::WEEKLY,
            static::MONTHLY,
            static::YEARLY,
            static::NEVER,
        ]);
    }

    /**
     * Get all the valid frequency values.
     *
     * @param  string|null  $locale
     *
     * @return \Illuminate\Support\Collection
     */
    public static function all(string $locale = null): Collection
    {
        return static::keys()->mapWithKeys(function ($key) use ($locale) {
            return [$key => trans("sitemap::frequencies.$key", [], $locale)];
        });
    }

    /**
     * Get the translated frequency name.
     *
     * @param  string       $key
     * @param  string|null  $default
     * @param  string|null  $locale
     *
     * @return string|mixed|null
     */
    public static function get($key, $default = null, string $locale = null)
    {
        return static::all($locale)->get($key, $default);
    }

    /**
     * Check if the given frequency exists.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public static function has(string $key): bool
    {
        return static::keys()->flip()->has($key);
    }
}
