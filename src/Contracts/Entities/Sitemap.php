<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSitemap\Contracts\Entities;

use Countable;
use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use Illuminate\Support\Collection;
use JsonSerializable;

/**
 * Interface  Sitemap
 *
 * @package   Arcanedev\LaravelSitemap\Contracts\Entities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Sitemap extends Arrayable, Countable, Jsonable, JsonSerializable
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the sitemap path.
     *
     * @param  string  $path
     *
     * @return $this
     */
    public function setPath(string $path);

    /**
     * Get the sitemap path.
     *
     * @return string|null
     */
    public function getPath(): ?string;

    /**
     * Get the sitemap's URLs.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getUrls(): Collection;

    /**
     * Set the URLs Collection.
     *
     * @param  \Illuminate\Support\Collection  $urls
     *
     * @return $this
     */
    public function setUrls(Collection $urls);

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make a sitemap instance.
     *
     * @return $this
     */
    public static function make();

    /**
     * Get a URL instance by its loc.
     *
     * @param  string      $loc
     * @param  mixed|null  $default
     *
     * @return \Arcanedev\LaravelSitemap\Entities\Url|null
     */
    public function getUrl(string $loc, $default = null);

    /**
     * Add a sitemap URL to the collection.
     *
     * @param  \Arcanedev\LaravelSitemap\Contracts\Entities\Url  $url
     *
     * @return $this
     */
    public function add(Url $url);

    /**
     * Add many urls to the collection.
     *
     * @param  iterable|mixed  $urls
     *
     * @return $this
     */
    public function addMany(iterable $urls);

    /**
     * Create and Add a sitemap URL to the collection.
     *
     * @param  string    $loc
     * @param  callable  $callback
     *
     * @return $this
     */
    public function create(string $loc, callable $callback);

    /**
     * Check if the url exists in the sitemap items.
     *
     * @param  string  $url
     *
     * @return bool
     */
    public function has(string $url): bool;

    /**
     * Check if the number of URLs is exceeded.
     *
     * @return bool
     */
    public function isExceeded(): bool;

    /**
     * Chunk the sitemap to multiple chunks if the size is exceeded.
     *
     * @return \Illuminate\Support\Collection
     */
    public function chunk(): Collection;
}
