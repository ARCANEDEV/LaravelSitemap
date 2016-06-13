<?php namespace Arcanedev\LaravelSitemap\Contracts;

/**
 * Interface  SitemapManager
 *
 * @package   Arcanedev\LaravelSitemap\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface SitemapManager
{
    /* ------------------------------------------------------------------------------------------------
     |  Getter and Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get all sitemaps.
     *
     * @return array
     */
    public function getSitemaps();

    /**
     * Adds sitemap to $sitemaps array.
     *
     * @param  array  $sitemap
     *
     * @return self
     */
    public function setSitemaps(array $sitemap);

    /**
     * Get the sitemap items.
     *
     * @return \Arcanedev\LaravelSitemap\Entities\SitemapItemCollection
     */
    public function getItems();

    /**
     * Get the title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set the title.
     *
     * @param  string  $title
     *
     * @return self
     */
    public function setTitle($title);

    /**
     * Get the link.
     *
     * @return string
     */
    public function getLink();

    /**
     * Sets $link value.
     *
     * @param  string  $link
     *
     * @return self
     */
    public function setLink($link);

    /**
     * Get the sitemap styles location.
     *
     * @return string
     */
    public function getStyleLocation();

    /**
     * Set the sitemap styles location.
     *
     * @param  string  $location
     *
     * @return self
     */
    public function setStyleLocation($location);

    /**
     * Get the escaping value.
     *
     * @return bool
     */
    public function isEscaped();

    /**
     * Set the escaping value.
     *
     * @param  bool  $escape
     *
     * @return self
     */
    public function setEscaping($escape);

    /**
     * Checks if content is cached
     *
     * @return bool
     */
    public function isCached();

    /**
     * Get the max size value.
     *
     * @return int
     */
    public function getMaxSize();

    /**
     * Set the max size value.
     *
     * @param  int  $maxSize
     *
     * @return self
     */
    public function setMaxSize($maxSize);

    /**
     * Set cache options
     *
     * @param  string|null                        $key
     * @param  \Carbon\Carbon|\Datetime|int|null  $duration
     * @param  bool                               $useCache
     */
    public function setCache($key = null, $duration = null, $useCache = true);

    /**
     * Get the cache enabled value.
     *
     * @return bool
     */
    public function isCacheEnabled();

    /**
     * Set the cache enabled value.
     *
     * @param  bool  $cacheEnabled
     *
     * @return self
     */
    public function setCacheEnabled($cacheEnabled);

    /**
     * Get the cache key value.
     *
     * @return string
     */
    public function getCacheKey();

    /**
     * Set the cache key value.
     *
     * @param  string  $key
     *
     * @return self
     */
    public function setCacheKey($key);

    /**
     * Get the cache duration value.
     *
     * @return int
     */
    public function getCacheDuration();

    /**
     * Set cache duration value.
     *
     * @param  int  $duration
     *
     * @return self
     */
    public function setCacheDuration($duration);

    /**
     * Get the use limit size value.
     *
     * @return bool
     */
    public function getUseLimitSize();

    /**
     * Set the use limit size value.
     *
     * @param  bool  $useLimitSize
     *
     * @return self
     */
    public function setUseLimitSize($useLimitSize);

    /**
     * Limit size of $items array to 50000 elements (1000 for google-news).
     *
     * @param  int  $max
     *
     * @return $this
     */
    public function limitSize($max = 50000);

    /**
     * Get the use styles value.
     *
     * @return bool
     */
    public function getUseStyles();

    /**
     * Set the use styles value.
     *
     * @param  bool  $useStyles
     *
     * @return self
     */
    public function setUseStyles($useStyles);

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Add new sitemap item to $items array.
     *
     * @param  string       $loc
     * @param  string|null  $lastmod
     * @param  string|null  $priority
     * @param  string|null  $freq
     * @param  array        $images
     * @param  string|null  $title
     * @param  array        $translations
     * @param  array        $videos
     * @param  array        $googlenews
     * @param  array        $alternates
     */
    public function add(
        $loc, $lastmod = null, $priority = null, $freq = null, $images = [], $title = null,
        $translations = [], $videos = [], $googlenews = [], $alternates = []
    );

    /**
     * Add new sitemap one or multiple items to $items array.
     *
     * @param  array  $params
     */
    public function addItem($params = []);

    /**
     * Add multiple sitemap items.
     *
     * @param  array  $items
     */
    public function addItems(array $items);

    /**
     * Add new sitemap to $sitemaps array.
     *
     * @param  string       $loc
     * @param  string|null  $lastmod
     */
    public function addSitemap($loc, $lastmod = null);

    /**
     * Returns document with all sitemap items from $items array.
     *
     * @param  string  $format  (options: xml, html, txt, ror-rss, ror-rdf, google-news)
     * @param  string  $style   (path to custom xls style like '/styles/xsl/xml-sitemap.xsl')
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|array
     */
    public function render($format = 'xml', $style = null);

    /**
     * Generates document with all sitemap items from $items array.
     *
     * @param  string       $format  (options: xml, html, txt, ror-rss, ror-rdf, sitemapindex, google-news)
     * @param  string|null  $style   (path to custom xls style like '/styles/xsl/xml-sitemap.xsl')
     *
     * @return array
     */
    public function generate($format = 'xml', $style = null);

    /**
     * Generate sitemap and store it to a file.
     *
     * @param  string  $format    (options: xml, html, txt, ror-rss, ror-rdf, sitemapindex, google-news)
     * @param  string  $filename  (without file extension, may be a path like 'sitemaps/sitemap1' but must exist)
     * @param  string  $path      (path to store sitemap like '/www/site/public')
     * @param  string  $style     (path to custom xls style like '/styles/xsl/xml-sitemap.xsl')
     *
     * @return bool
     */
    public function store($format = 'xml', $filename = 'sitemap', $path = null, $style = null);

    /**
     * Reset the sitemaps container.
     *
     * @param  array  $sitemaps
     *
     * @return self
     */
    public function resetSitemaps(array $sitemaps = []);

    /**
     * Reset the items array.
     *
     * @param  array  $items
     *
     * @return self
     */
    public function resetItems(array $items = []);
}
