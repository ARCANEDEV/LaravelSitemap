<?php namespace Arcanedev\LaravelSitemap\Contracts\Entities;

/**
 * Interface  ChangeFrequency
 *
 * @package   Arcanedev\LaravelSitemap\Contracts\Entities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface ChangeFrequency
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    const ALWAYS  = 'always';
    const HOURLY  = 'hourly';
    const DAILY   = 'daily';
    const WEEKLY  = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY  = 'yearly';
    const NEVER   = 'never';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get all the valid frequency keys.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function keys();

    /**
     * Get all the valid frequency values.
     *
     * @param  string|null  $locale
     *
     * @return \Illuminate\Support\Collection
     */
    public static function all($locale = null);

    /**
     * Get the translated frequency name.
     *
     * @param  string       $key
     * @param  string|null  $default
     * @param  string|null  $locale
     *
     * @return string|null
     */
    public static function get($key, $default = null, $locale = null);

    /**
     * Check if the given frequency exists.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public static function has($key);
}
