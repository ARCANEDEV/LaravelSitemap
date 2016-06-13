<?php namespace Arcanedev\LaravelSitemap\Entities;

use Arcanedev\LaravelSitemap\Contracts\SitemapFrequency as SitemapFrequencyContract;

/**
 * Class     SitemapFrequency
 *
 * @package  Arcanedev\LaravelSitemap\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapFrequency implements SitemapFrequencyContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get all the valid frequency keys.
     *
     * @return array
     */
    public static function keys()
    {
        return [
            self::ALWAYS, self::HOURLY, self::DAILY, self::WEEKLY, self::MONTHLY, self::YEARLY, self::NEVER
        ];
    }

    /**
     * Get all the valid frequency values.
     *
     * @param  string|null  $locale
     *
     * @return array
     */
    public static function all($locale = null)
    {
        return array_map(function ($key) use ($locale) {
            return self::get($key, $locale);
        }, array_combine(self::keys(), self::keys()));
    }

    /**
     * Get the translated frequency name.
     *
     * @param  string       $key
     * @param  string|null  $locale
     *
     * @return string
     */
    public static function get($key, $locale = null)
    {
        return trans("sitemap::frequencies.$key", [], 'messages', $locale);
    }

    /**
     * Check if the frequency is valid value.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public static function exists($key)
    {
        return in_array($key, self::keys());
    }
}
