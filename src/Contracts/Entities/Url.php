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
     * Get the url location (alias).
     *
     * @see getLoc()
     *
     * @return string
     */
    public function loc();

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
     * Get the last modification date (alias).
     *
     * @see getLastMod()
     *
     * @return \DateTimeInterface
     */
    public function lastMod();

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
     * Get the change frequency (alias).
     *
     * @see getChangeFreq()
     *
     * @return string
     */
    public function changeFreq();

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
     * Get the priority (alias).
     *
     * @see getPriority()
     *
     * @return float
     */
    public function priority();

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
}
