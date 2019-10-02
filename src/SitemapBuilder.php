<?php namespace Arcanedev\LaravelSitemap;

use Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap;
use DOMDocument;
use Illuminate\Support\{Collection, Str};

/**
 * Class     SitemapBuilder
 *
 * @package  Arcanedev\LaravelSitemap
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapBuilder
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create the builder instance.
     *
     * @return \Arcanedev\LaravelSitemap\SitemapBuilder
     */
    public static function make()
    {
        return new static();
    }

    /**
     * Build the sitemap.
     *
     * @param  string                          $name
     * @param  \Illuminate\Support\Collection  $sitemaps
     * @param  string                          $format
     *
     * @throws \Throwable
     *
     * @return string|null
     */
    public function build($name, Collection $sitemaps, $format)
    {
        if ($sitemaps->isEmpty())
            return null;

        if (is_null($name)) {
            return $sitemaps->count() > 1
                ? static::renderSitemapIndex($format, $sitemaps)
                : static::renderSitemap($format, $sitemaps->first());
        }

        list($name, $key) = Str::contains($name, '.')
            ? explode('.', $name, 2)
            : [$name, null];

        if ($sitemaps->has($name))
            return static::renderSitemap($format, $sitemaps->get($name), $key);

        return null;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Build a single sitemap item.
     *
     * @param  string                                                     $format
     * @param  \Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap|null  $sitemap
     * @param  string|null                                                $key
     *
     * @throws \Throwable
     *
     * @return string|null
     */
    protected function renderSitemap($format, Sitemap $sitemap = null, $key = null)
    {
        if (is_null($sitemap))
            return null;

        if ( ! $sitemap->isExceeded())
            return static::render($format, 'sitemap', ['sitemap' => $sitemap]);

        $chunks = $sitemap->chunk();

        return is_null($key)
            ? static::renderSitemapIndex($format, $chunks)
            : static::renderSitemap($format, $chunks->get($key));
    }

    /**
     * Render sitemap index.
     *
     * @param  string                          $format
     * @param  \Illuminate\Support\Collection  $sitemaps
     *
     * @throws \Throwable
     *
     * @return string|null
     */
    protected function renderSitemapIndex($format, $sitemaps)
    {
        return static::render($format, 'sitemap-index', compact('sitemaps'));
    }

    /**
     * Render the file.
     *
     * @param  string  $format
     * @param  string  $type
     * @param  array   $data
     *
     * @throws \Throwable
     *
     * @return string|null
     */
    protected function render($format, $type, array $data)
    {
        switch ($format= strtolower(trim($format))) {
            case 'xml':
            case 'rss':
                return static::renderXml($format, $type, $data);

            case 'txt':
                return static::renderView($type, $format, $data);

            default:
                return null;
        }
    }

    /**
     * Render the xml file.
     *
     * @param  string  $format
     * @param  string  $type
     * @param  array   $data
     *
     * @return string
     */
    protected function renderXml($format, $type, array $data)
    {
        return tap(new DOMDocument('1.0'), function (DOMDocument $document) use ($format, $type, $data) {
            $document->preserveWhiteSpace = false;
            $document->formatOutput = true;
            $document->loadXML(
                static::renderView($type, $format, $data)
            );
        })->saveXML();
    }

    /**
     * Render with illuminate.
     *
     * @param  string  $type
     * @param  string  $format
     * @param  array   $data
     *
     * @throws \Throwable
     *
     * @return string
     */
    protected function renderView($type, $format, array $data)
    {
        return view("sitemap::{$type}.{$format}", $data)->render();
    }
}
