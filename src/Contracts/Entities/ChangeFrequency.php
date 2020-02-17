<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSitemap\Contracts\Entities;

use Illuminate\Support\Collection;

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
    public static function keys(): Collection;

    /**
     * Get all the valid frequency values.
     *
     * @param  string|null  $locale
     *
     * @return \Illuminate\Support\Collection
     */
    public static function all(string $locale = null): Collection;

    /**
     * Get the translated frequency name.
     *
     * @param  string       $key
     * @param  mixed|null   $default
     * @param  string|null  $locale
     *
     * @return string|mixed|null
     */
    public static function get(string $key, $default = null, string $locale = null);

    /**
     * Check if the given frequency exists.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public static function has(string $key): bool ;
}
