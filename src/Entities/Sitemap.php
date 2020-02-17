<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSitemap\Entities;

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
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the sitemap path.
     *
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * Get the sitemap's URLs.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getUrls(): Collection
    {
        return $this->urls;
    }

    /**
     * Set the URLs Collection.
     *
     * @param  \Illuminate\Support\Collection  $urls
     *
     * @return $this
     */
    public function setUrls(Collection $urls)
    {
        $this->urls = $urls;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make a sitemap instance.
     *
     * @return $this
     */
    public static function make()
    {
        return new static();
    }

    /**
     * Get a URL instance by its loc.
     *
     * @param  string      $loc
     * @param  mixed|null  $default
     *
     * @return \Arcanedev\LaravelSitemap\Entities\Url|null
     */
    public function getUrl(string $loc, $default = null)
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
     * Add many urls to the collection.
     *
     * @param  iterable|mixed  $urls
     *
     * @return $this
     */
    public function addMany(iterable $urls)
    {
        foreach ($urls as $url) {
            $this->add($url);
        }

        return $this;
    }

    /**
     * Create and Add a sitemap URL to the collection.
     *
     * @param  string    $loc
     * @param  callable  $callback
     *
     * @return $this
     */
    public function create(string $loc, callable $callback)
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
    public function has(string $url): bool
    {
        return $this->urls->has($url);
    }

    /**
     * Get the urls' count.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->urls->count();
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray(): array
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
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Check if the number of URLs is exceeded.
     *
     * @return bool
     */
    public function isExceeded(): bool
    {
        return $this->count() > $this->getMaxSize();
    }

    /**
     * Chunk the sitemap to multiple chunks if the size is exceeded.
     *
     * @return \Illuminate\Support\Collection
     */
    public function chunk(): Collection
    {
        return $this->urls
            ->chunk($this->getMaxSize())
            ->mapWithKeys(function ($item, $index) {
                $pathInfo = pathinfo($this->getPath());
                $index    = $index + 1;
                $path     = $pathInfo['dirname'].'/'.$pathInfo['filename'].'-'.$index.'.'.$pathInfo['extension'];

                return [
                    $index => (new Sitemap)->setPath($path)->setUrls($item),
                ];
            });
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the max size.
     *
     * @return int
     */
    protected function getMaxSize(): int
    {
        return (int) config('sitemap.urls-max-size', 50000);
    }
}
