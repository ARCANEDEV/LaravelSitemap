<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSitemap\Contracts\Entities;

use DateTimeInterface;
use Illuminate\Contracts\Support\{Arrayable, Jsonable};
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
    public function getLoc(): string;

    /**
     * Set the url location.
     *
     * @param  string  $loc
     *
     * @return $this
     */
    public function setLoc($loc);

    /**
     * Get the last modification date.
     *
     * @return \DateTimeInterface
     */
    public function getLastMod(): DateTimeInterface;

    /**
     * Format the url last modification.
     *
     * @param  string  $format
     *
     * @return string
     */
    public function formatLastMod(string $format = DateTimeInterface::ATOM): string;

    /**
     * Set the last modification date.
     *
     * @param  string|\DateTimeInterface  $lastModDate
     * @param  string                     $format
     *
     * @return $this
     */
    public function setLastMod($lastModDate, string $format = 'Y-m-d H:i:s');

    /**
     * Get the change frequency.
     *
     * @return string
     */
    public function getChangeFreq(): string;

    /**
     * Set the change frequency.
     *
     * @param  string  $changeFreq
     *
     * @return $this
     */
    public function setChangeFreq(string $changeFreq);

    /**
     * Get the priority.
     *
     * @return float
     */
    public function getPriority(): float;

    /**
     * Set the priority.
     *
     * @param  float|mixed  $priority
     *
     * @return $this
     */
    public function setPriority($priority);

    /**
     * Get the title.
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Get the title.
     *
     * @param  string|null  $title
     *
     * @return $this
     */
    public function setTitle(?string $title);

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
     * @return $this
     */
    public function set(string $key, $value);

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a sitemap url instance.
     *
     * @param  string  $loc
     *
     * @return $this
     */
    public static function make($loc);

    /**
     * Make a URL instance with attributes.
     *
     * @param  array  $attributes
     *
     * @return $this
     */
    public static function makeFromArray(array $attributes);

    /**
     * Check if has an attribute.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function has(string $key): bool;
}
