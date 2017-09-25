<?php namespace Arcanedev\LaravelSitemap\Contracts\Entities;

use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
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
     * @return self
     */
    public function setPath($path);

    /**
     * Get the sitemap path.
     *
     * @return string|null
     */
    public function getPath();

    /**
     * Get the sitemap's URLs.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getUrls();

    /**
     * Set the URLs Collection.
     *
     * @param  \Illuminate\Support\Collection  $urls
     *
     * @return self
     */
    public function setUrls(Collection $urls);

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make a sitemap instance.
     *
     * @return self
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
    public function getUrl($loc, $default = null);

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
     * @param  array  $urls
     *
     * @return self
     */
    public function addMany($urls);

    /**
     * Create and Add a sitemap URL to the collection.
     *
     * @param  string    $loc
     * @param  callable  $callback
     *
     * @return self
     */
    public function create($loc, callable $callback);

    /**
     * Check if the url exists in the sitemap items.
     *
     * @param  string  $url
     *
     * @return bool
     */
    public function has($url);

    /**
     * Check if the number of URLs is exceeded.
     *
     * @return bool
     */
    public function isExceeded();

    /**
     * Chunk the sitemap to multiple chunks if the size is exceeded.
     *
     * @return \Illuminate\Support\Collection
     */
    public function chunk();
}
