<?php namespace Arcanedev\LaravelSitemap;

use Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap as SitemapContract;
use Arcanedev\LaravelSitemap\Contracts\SitemapManager as SitemapManagerContract;
use Arcanedev\LaravelSitemap\Entities\Sitemap;
use Illuminate\Support\Collection;

/**
 * Class     SitemapManager
 *
 * @package  Arcanedev\LaravelSitemap
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapManager implements SitemapManagerContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Illuminate\Support\Collection */
    protected $sitemaps;

    /** @var  string */
    protected $format = 'xml';

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * SitemapManager constructor.
     */
    public function __construct()
    {
        $this->sitemaps = new Collection;
    }

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
    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

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
    public function create($name, callable $callback)
    {
        return $this->add($name, tap((new Sitemap)->setPath($name), $callback));
    }

    /**
     * Add a sitemap to the collection.
     *
     * @param  string                                                $name
     * @param  \Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap  $sitemap
     *
     * @return self
     */
    public function add($name, SitemapContract $sitemap)
    {
        $this->sitemaps->put($name, $sitemap);

        return $this;
    }

    /**
     * Get the sitemaps collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->sitemaps;
    }

    /**
     * Get a sitemap instance.
     *
     * @param  string      $name
     * @param  mixed|null  $default
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return $this->sitemaps->get($name, $default);
    }

    /**
     * Check if a sitemap exists.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function has($name)
    {
        return $this->sitemaps->has($name);
    }

    /**
     * Remove a sitemap from the collection by key.
     *
     * @param  string|array  $names
     *
     * @return self
     */
    public function forget($names)
    {
        $this->sitemaps->forget($names);

        return $this;
    }

    /**
     * Get the sitemaps count.
     *
     * @return int
     */
    public function count()
    {
        return $this->sitemaps->count();
    }

    /**
     * Render the sitemaps.
     *
     * @param  string  $name
     *
     * @return string|null
     */
    public function render($name = null)
    {
        return SitemapBuilder::make()->build($name, $this->sitemaps, $this->format);
    }

    /**
     * Save the sitemaps.
     *
     * @param  string       $path
     * @param  string|null  $name
     *
     * @return self
     */
    public function save($path, $name = null)
    {
        if ($this->sitemaps->isEmpty())
            return $this;

        file_put_contents($path, $this->render($name));

        /** @var  \Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap  $sitemap */
        foreach ($this->sitemaps as $key => $sitemap) {
            if ($sitemap->isExceeded())
                $this->saveMultiple($path, $sitemap);
        }

        return $this;
    }

    /**
     * Render the Http response.
     *
     * @param  string  $name
     * @param  int     $status
     * @param  array   $headers
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function respond($name = null, $status = 200, array $headers = [])
    {
        return response($this->render($name), $status, array_merge($this->getResponseHeaders(), $headers));
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->all()->toArray();
    }

    /**
     * Get the collection of sitemaps as JSON.
     *
     * @param  int  $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Save multiple sitemap.
     *
     * @param  string                                                $path
     * @param  \Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap  $sitemap
     */
    private function saveMultiple($path, $sitemap)
    {
        $pathInfo = pathinfo($path);
        $chunks   = $sitemap->chunk();

        foreach ($chunks as $key => $item) {
            file_put_contents(
                $pathInfo['dirname'].DS.$pathInfo['filename'].'-'.$key.'.'.$pathInfo['extension'],
                SitemapBuilder::make()->build($key, $chunks, $this->format)
            );
        }
    }

    /**
     * Get the response header.
     *
     * @return array
     */
    protected function getResponseHeaders()
    {
        return array_get([
            'xml' => ['Content-Type' => 'application/xml'],
            'rss' => ['Content-Type' => 'application/rss+xml'],
            'txt' => ['Content-Type' => 'text/plain'],
        ], $this->format, []);
    }
}
