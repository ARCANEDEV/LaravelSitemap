<?php namespace Arcanedev\LaravelSitemap\Contracts\Entities;

use DateTime;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

/**
 * Interface  Url
 *
 * @package   Arcanedev\LaravelSitemap\Contracts\Entities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Url extends Arrayable, Jsonable, JsonSerializable
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the url location.
     *
     * @return string
     */
    public function getLoc();

    /**
     * Set the url location.
     *
     * @param  string  $loc
     *
     * @return self
     */
    public function setLoc($loc);

    /**
     * Get the last modification date.
     *
     * @return \DateTimeInterface
     */
    public function getLastMod();

    /**
     * Format the url last modification.
     *
     * @param  string  $format
     *
     * @return string
     */
    public function formatLastMod($format = DateTime::ATOM);

    /**
     * Set the last modification date.
     *
     * @param  string|\DateTimeInterface  $lastModDate
     * @param  string                     $format
     *
     * @return self
     */
    public function setLastMod($lastModDate, $format = 'Y-m-d H:i:s');

    /**
     * Get the change frequency.
     *
     * @return string
     */
    public function getChangeFreq();

    /**
     * Set the change frequency.
     *
     * @param  string  $changeFreq
     *
     * @return self
     */
    public function setChangeFreq($changeFreq);

    /**
     * Get the priority.
     *
     * @return float
     */
    public function getPriority();

    /**
     * Set the priority.
     *
     * @param  float  $priority
     *
     * @return self
     */
    public function setPriority($priority);

    /**
     * Get the title.
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get the title.
     *
     * @param  string  $title
     *
     * @return self
     */
    public function setTitle($title);

    /**
     * Get an attribute from the container.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Set an attribute into the container.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return self
     */
    public function set($key, $value);

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a sitemap url instance.
     *
     * @param  string  $loc
     *
     * @return \Arcanedev\LaravelSitemap\Entities\Url
     */
    public static function make($loc);

    /**
     * Make a URL instance with attributes.
     *
     * @param  array  $attributes
     *
     * @return \Arcanedev\LaravelSitemap\Entities\Url
     */
    public static function makeFromArray(array $attributes);

    /**
     * Check if has an attribute.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function has($key);
}
