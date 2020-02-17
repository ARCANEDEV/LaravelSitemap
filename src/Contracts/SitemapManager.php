<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSitemap\Contracts;

use Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap;
use Countable;
use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use Illuminate\Support\Collection;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;

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
     * @return $this
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
     * @return $this
     */
    public function create(string $name, callable $callback);

    /**
     * Add a sitemap to the collection.
     *
     * @param  string                                                $name
     * @param  \Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap  $sitemap
     *
     * @return $this
     */
    public function add(string $name, Sitemap $sitemap);

    /**
     * Get the sitemaps collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection;

    /**
     * Get a sitemap instance.
     *
     * @param  string      $name
     * @param  mixed|null  $default
     *
     * @return \Arcanedev\LaravelSitemap\Entities\Sitemap|mixed|null
     */
    public function get(string $name, $default = null);

    /**
     * Check if a sitemap exists.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * Remove a sitemap from the collection by key.
     *
     * @param  string|array  $names
     *
     * @return $this
     */
    public function forget($names);

    /**
     * Render the sitemaps.
     *
     * @param  string|null  $name
     *
     * @return string|null
     */
    public function render(string $name = null): ?string;

    /**
     * Save the sitemaps.
     *
     * @param  string       $path
     * @param  string|null  $name
     *
     * @return $this
     */
    public function save(string $path, string $name = null);

    /**
     * Render the Http response.
     *
     * @param  string  $name
     * @param  int     $status
     * @param  array   $headers
     *
     * @return \Illuminate\Http\Response|mixed
     */
    public function respond(string $name = null, int $status = Response::HTTP_OK, array $headers = []);
}
