<?php namespace Arcanedev\LaravelSitemap\Contracts;

/**
 * Interface  SitemapFrequency
 *
 * @package   Arcanedev\LaravelSitemap\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface SitemapFrequency
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const ALWAYS  = 'always';
    const HOURLY  = 'hourly';
    const DAILY   = 'daily';
    const WEEKLY  = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY  = 'yearly';
    const NEVER   = 'never';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get all the valid frequency keys.
     *
     * @return array
     */
    public static function keys();

    /**
     * Get all the valid frequency values.
     *
     * @param  string|null  $locale
     *
     * @return array
     */
    public static function all($locale = null);

    /**
     * Get the translated frequency name.
     *
     * @param  string       $key
     * @param  string|null  $locale
     *
     * @return string
     */
    public static function get($key, $locale = null);

    /**
     * Check if the frequency is valid value.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public static function exists($key);
}
