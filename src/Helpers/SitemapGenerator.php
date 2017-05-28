<?php namespace Arcanedev\LaravelSitemap\Helpers;

use Arcanedev\LaravelSitemap\Contracts\SitemapGenerator as SitemapGeneratorContract;
use Arcanedev\LaravelSitemap\Contracts\SitemapStyler as SitemapStylerContract;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\View\Factory as View;
use Illuminate\Support\Arr;

/**
 * Class     SitemapGenerator
 *
 * @package  Arcanedev\LaravelSitemap\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapGenerator implements SitemapGeneratorContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Illuminate\Contracts\View\Factory */
    protected $view;

    /** @var  \Illuminate\Contracts\Cache\Repository */
    protected $cache;

    /** @var  \Arcanedev\LaravelSitemap\Contracts\SitemapStyler */
    protected $styler;

    /** @var  array */
    protected $contentTypes = [
        'html'         => 'text/html',
        'ror-rdf'      => 'text/rdf+xml; charset=utf-8',
        'ror-rss'      => 'text/rss+xml; charset=utf-8',
        'txt'          => 'text/plain',
        'sitemapindex' => 'text/xml; charset=utf-8',
        'xml'          => 'text/xml; charset=utf-8'
    ];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * SitemapGenerator constructor.
     *
     * @param  \Arcanedev\LaravelSitemap\Contracts\SitemapStyler  $styler
     * @param  \Illuminate\Contracts\Cache\Repository             $cache
     * @param  \Illuminate\Contracts\View\Factory                 $view
     */
    public function __construct(
        SitemapStylerContract $styler,
        Cache $cache,
        View $view
    ) {
        $this->styler = $styler;
        $this->cache  = $cache;
        $this->view   = $view;
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the sitemap styles location.
     *
     * @return string
     */
    public function getStylesLocation()
    {
        return $this->styler->getLocation();
    }

    /**
     * Set the sitemap styles location.
     *
     * @param  string  $location
     *
     * @return self
     */
    public function setStylesLocation($location)
    {
        $this->styler->setLocation($location);

        return $this;
    }

    /**
     * Set the use styles value.
     *
     * @param  bool $useStyles
     *
     * @return self
     */
    public function setUseStyles($useStyles)
    {
        $this->styler->setEnabled($useStyles);

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * @param  array        $data
     * @param  string       $format
     * @param  string|null  $style
     *
     * @return array
     */
    public function generate(array $data, $format = 'xml', $style = null)
    {
        // check if styles are enabled
        $data['style'] = $this->styler->get($format, $style);

        $content = $this->getContent($data, $format);
        $headers = $this->getHeaders($format);

        return compact('content', 'headers');
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check is styles is enabled.
     *
     * @return bool
     */
    public function isStylesEnabled()
    {
        return $this->styler->isEnabled();
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the rendered content.
     *
     * @param  array   $data
     * @param  string  $format
     *
     * @return string
     */
    private function getContent(array $data, $format)
    {
        $content = $this->view->make("sitemap::$format", $data)->render();

        return $content;
    }

    /**
     * Get the headers.
     *
     * @param  string  $format
     *
     * @return array
     */
    protected function getHeaders($format)
    {
        return [
            'Content-type' => Arr::get($this->contentTypes, $format, 'text/xml; charset=utf-8')
        ];
    }
}
