<?php namespace Arcanedev\LaravelSitemap\Contracts;

use Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

/**
 * Interface  SitemapManager
 *
 * @package   Arcanedev\LaravelSitemap\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface SitemapManager extends Arrayable, Countable, Jsonable, JsonSerializable
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the format.
     *
     * @param  string  $format
     *
     * @return self
     */
    public function format($format);

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create and add a sitemap to the collection.
     *
     * @param  string    $name
     * @param  callable  $callback
     *
     * @return self
     */
    public function create($name, callable $callback);

    /**
     * Add a sitemap to the collection.
     *
     * @param  string                                                $name
     * @param  \Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap  $sitemap
     *
     * @return self
     */
    public function add($name, Sitemap $sitemap);

    /**
     * Get the sitemaps collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all();

    /**
     * Get a sitemap instance.
     *
     * @param  string      $name
     * @param  mixed|null  $default
     *
     * @return \Arcanedev\LaravelSitemap\Entities\Sitemap|null
     */
    public function get($name, $default = null);

    /**
     * Check if a sitemap exists.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function has($name);

    /**
     * Remove a sitemap from the collection by key.
     *
     * @param  string|array  $names
     *
     * @return self
     */
    public function forget($names);

    /**
     * Render the sitemaps.
     *
     * @param  string|null  $name
     *
     * @return string|null
     */
    public function render($name = null);

    /**
     * Save the sitemaps.
     *
     * @param  string       $path
     * @param  string|null  $name
     *
     * @return self
     */
    public function save($path, $name = null);

    /**
     * Render the Http response.
     *
     * @param  string  $name
     * @param  int     $status
     * @param  array   $headers
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function respond($name = null, $status = 200, array $headers = []);
}
