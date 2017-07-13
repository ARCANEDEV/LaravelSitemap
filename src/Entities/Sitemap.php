<?php namespace Arcanedev\LaravelSitemap\Entities;

use Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap as SitemapContract;
use Illuminate\Support\Collection;
use Arcanedev\LaravelSitemap\Contracts\Entities\Url as UrlContract;

/**
 * Class     Sitemap
 *
 * @package  Arcanedev\LaravelSitemap\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Sitemap implements SitemapContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var string|null */
    protected $path;

    /** @var  \Illuminate\Support\Collection */
    protected $urls;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Sitemap constructor.
     */
    public function __construct()
    {
        $this->urls = new Collection;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Set the sitemap path.
     *
     * @param  string  $path
     *
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the sitemap path.
     *
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the sitemap's URLs.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * Set the URLs Collection.
     *
     * @param  \Illuminate\Support\Collection  $urls
     *
     * @return self
     */
    public function setUrls(Collection $urls)
    {
        $this->urls = $urls;

        return $this;
    }

    /**
     * Get a URL instance by its loc.
     *
     * @param  string      $loc
     * @param  mixed|null  $default
     *
     * @return \Arcanedev\LaravelSitemap\Entities\Url|null
     */
    public function getUrl($loc, $default = null)
    {
        return $this->getUrls()->get($loc, $default);
    }

    /**
     * Add a sitemap URL to the collection.
     *
     * @param  \Arcanedev\LaravelSitemap\Contracts\Entities\Url  $url
     *
     * @return $this
     */
    public function add(UrlContract $url)
    {
        $this->urls->put($url->getLoc(), $url);

        return $this;
    }

    /**
     * Create and Add a sitemap URL to the collection.
     *
     * @param  string    $loc
     * @param  callable  $callback
     *
     * @return self
     */
    public function create($loc, callable $callback)
    {
        return $this->add(tap(Url::make($loc), $callback));
    }

    /**
     * Check if the url exists in the sitemap items.
     *
     * @param  string  $url
     *
     * @return bool
     */
    public function has($url)
    {
        return $this->urls->has($url);
    }

    /**
     * Get the urls' count.
     *
     * @return int
     */
    public function count()
    {
        return $this->urls->count();
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getUrls()->values()->toArray();
    }

    /**
     * Get the sitemap and its urls as JSON.
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
     * Check if the number of URLs is exceeded.
     *
     * @return bool
     */
    public function isExceeded()
    {
        return $this->count() > $this->getMaxSize();
    }

    /**
     * Chunk the sitemap to multiple chunks.
     *
     * @return \Illuminate\Support\Collection
     */
    public function chunk()
    {
        return $this->urls->chunk($this->getMaxSize())->mapWithKeys(function ($item, $index) {
            $pathInfo = pathinfo($this->getPath());
            $index    = $index + 1;
            $path     = $pathInfo['dirname'].'/'.$pathInfo['filename'].'-'.$index.'.'.$pathInfo['extension'];

            return [$index => (new Sitemap)->setPath($path)->setUrls($item)];
        });
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    protected function getMaxSize()
    {
        return config('sitemap.urls-max-size', 50000);
    }
}
