<?php namespace Arcanedev\LaravelSitemap;

use Arcanedev\LaravelSitemap\Contracts\SitemapGenerator;
use Arcanedev\LaravelSitemap\Contracts\SitemapManager as SitemapManagerContract;
use Arcanedev\LaravelSitemap\Contracts\SitemapStyler;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem as Filesystem;

/**
 * Class     SitemapManager
 *
 * @package  Arcanedev\LaravelSitemap
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapManager implements SitemapManagerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var array
     */
    protected $sitemaps = [];

    /**
     * @var string
     */
    protected $title = null;

    /**
     * @var string
     */
    protected $link = null;

    /**
     * @var Entities\SitemapItemCollection
     */
    protected $items;

    /**
     * Escaping html entities.
     *
     * @var bool
     */
    private $escaping = true;

    /**
     * Custom max size for limitSize()
     *
     * @var bool
     */
    protected $maxSize = null;

    /**
     * Enable or disable cache
     *
     * @var boolean
     */
    protected $cacheEnabled = false;

    /**
     * Unique cache key.
     *
     * @var string
     */
    protected $cacheKey = 'laravel-sitemap.your-key';

    /**
     * Cache duration, can be int or timestamp
     *
     * @var int
     */
    protected $cacheDuration = 3600;

    /**
     * Use limit size for big sitemaps
     *
     * @var bool
     */
    protected $useLimitSize;

    /** @var  \Illuminate\Contracts\Config\Repository */
    protected $config;

    /** @var  \Illuminate\Contracts\Cache\Repository */
    protected $cache;

    /** @var  \Illuminate\Filesystem\Filesystem */
    protected $filesystem;

    /** @var \Arcanedev\LaravelSitemap\Contracts\SitemapGenerator */
    private $generator;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * SitemapManager constructor.
     *
     * @param  \Illuminate\Contracts\Cache\Repository                $cache
     * @param  \Illuminate\Contracts\Config\Repository               $config
     * @param  \Illuminate\Filesystem\Filesystem                     $filesystem
     * @param  \Arcanedev\LaravelSitemap\Contracts\SitemapGenerator  $generator
     */
    public function __construct(
        Cache $cache,
        Config $config,
        Filesystem $filesystem,
        SitemapGenerator $generator
    ) {
        $this->cache      = $cache;
        $this->config     = $config;
        $this->filesystem = $filesystem;
        $this->generator = $generator;

        $this->init();
    }

    private function init()
    {
        $this->resetItems();
        $this->resetSitemaps();
        $this->setCache(
            $this->config->get('sitemap.cache.key',      $this->cacheKey),
            $this->config->get('sitemap.cache.lifetime', $this->cacheDuration),
            $this->config->get('sitemap.cache.enabled',  $this->cacheEnabled)
        );
        $this->setUseLimitSize($this->config->get('sitemap.use-limit-size', $this->useLimitSize));
        $this->setEscaping($this->config->get('sitemap.escaping', $this->escaping));
        $this->setMaxSize($this->config->get('sitemap.max-size', $this->maxSize));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get all sitemaps.
     *
     * @return array
     */
    public function getSitemaps()
    {
        return $this->sitemaps;
    }

    /**
     * Add a sitemap to the sitemap container.
     *
     * @param  array  $sitemap
     *
     * @return self
     */
    public function setSitemap(array $sitemap)
    {
        $this->sitemaps[] = $sitemap;

        return $this;
    }

    /**
     * Get the sitemap items.
     *
     * @return \Arcanedev\LaravelSitemap\Entities\SitemapItemCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the title.
     *
     * @param  string  $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the link.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets $link value.
     *
     * @param  string  $link
     *
     * @return self
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get the sitemap styles location.
     *
     * @return string
     */
    public function getStyleLocation()
    {
        return $this->generator->getStylesLocation();
    }

    /**
     * Set the sitemap styles location.
     *
     * @param  string  $location
     *
     * @return self
     */
    public function setStyleLocation($location)
    {
        $this->generator->setStylesLocation($location);

        return $this;
    }

    /**
     * Get the escaping value.
     *
     * @return bool
     */
    public function isEscaped()
    {
        return $this->escaping;
    }

    /**
     * Set the escaping value.
     *
     * @param  bool  $escape
     *
     * @return self
     */
    public function setEscaping($escape)
    {
        $this->escaping = (bool) $escape;

        return $this;
    }

    /**
     * Get the max size value.
     *
     * @return int
     */
    public function getMaxSize()
    {
        return $this->maxSize;
    }

    /**
     * Set the max size value.
     *
     * @param  int  $maxSize
     *
     * @return self
     */
    public function setMaxSize($maxSize)
    {
        $this->maxSize = $maxSize;

        return $this;
    }

    /**
     * Set cache options.
     *
     * @param  string|null                        $key
     * @param  \Carbon\Carbon|\Datetime|int|null  $duration
     * @param  bool                               $useCache
     */
    public function setCache($key = null, $duration = null, $useCache = true)
    {
        $this->setCacheEnabled($useCache);
        $this->setCacheKey($key);
        $this->setCacheDuration($duration);
    }

    /**
     * Get the cache enabled value.
     *
     * @return bool
     */
    public function isCacheEnabled()
    {
        return $this->cacheEnabled;
    }

    /**
     * Set the cache enabled value.
     *
     * @param  bool  $cacheEnabled
     *
     * @return self
     */
    public function setCacheEnabled($cacheEnabled)
    {
        $this->cacheEnabled = $cacheEnabled;

        return $this;
    }

    /**
     * Get the cache key value.
     *
     * @return string
     */
    public function getCacheKey()
    {
        return $this->cacheKey;
    }

    /**
     * Set the cache key value.
     *
     * @param  string  $key
     *
     * @return self
     */
    public function setCacheKey($key)
    {
        if ( ! is_null($key)) {
            $this->cacheKey = $key;
        }

        return $this;
    }

    /**
     * Get the cache duration value.
     *
     * @return int
     */
    public function getCacheDuration()
    {
        return $this->cacheDuration;
    }

    /**
     * Set cache duration value.
     *
     * @param  int  $duration
     *
     * @return self
     */
    public function setCacheDuration($duration)
    {
        if ( ! is_null($duration)) {
            $this->cacheDuration = $duration;
        }

        return $this;
    }

    /**
     * Checks if content is cached.
     *
     * @return bool
     */
    public function isCached()
    {
        return $this->isCacheEnabled() && $this->cache->has($this->getCacheKey());
    }

    /**
     * Get the use limit size value.
     *
     * @return bool
     */
    public function getUseLimitSize()
    {
        return $this->useLimitSize;
    }

    /**
     * Set the use limit size value.
     *
     * @param  bool  $useLimitSize
     *
     * @return self
     */
    public function setUseLimitSize($useLimitSize)
    {
        $this->useLimitSize = (bool) $useLimitSize;

        return $this;
    }

    /**
     * Limit size of $items array to 50000 elements (1000 for google-news).
     *
     * @param  int  $max
     *
     * @return self
     */
    public function limitSize($max = 50000)
    {
        $this->items = $this->items->slice(0, $max);

        return $this;
    }

    /**
     * Get the use styles value.
     *
     * @return bool
     */
    public function getUseStyles()
    {
        return $this->generator->isStylesEnabled();
    }

    /**
     * Set the use styles value.
     *
     * @param  bool  $useStyles
     *
     * @return self
     */
    public function setUseStyles($useStyles)
    {
        $this->generator->setUseStyles($useStyles);

        return $this;
    }

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
    ) {
        $this->addItem(compact(
            'loc', 'lastmod', 'priority', 'freq', 'images', 'title', 'translations', 'videos', 'googlenews', 'alternates'
        ));
    }

    /**
     * Add a new sitemap item.
     *
     * @param  array  $params
     */
    public function addItem($params = [])
    {
        $this->items->addItem($params, $this->isEscaped());
    }

    /**
     * Add multiple sitemap items.
     *
     * @param  array  $items
     */
    public function addItems(array $items)
    {
        foreach ($items as $item) { $this->addItem($item); }
    }

    /**
     * Add new sitemap to $sitemaps array.
     *
     * @param  string       $loc
     * @param  string|null  $lastmod
     */
    public function addSitemap($loc, $lastmod = null)
    {
        $this->setSitemap(compact('loc', 'lastmod'));
    }

    /**
     * Returns document with all sitemap items from $items array.
     *
     * @param  string  $format  (options: xml, html, txt, ror-rss, ror-rdf, google-news)
     * @param  string  $style   (path to custom xls style like '/styles/xsl/xml-sitemap.xsl')
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|array
     */
    public function render($format = 'xml', $style = null)
    {
        // limit size of sitemap
        if ($this->getMaxSize() > 0 && count($this->getItems()) > $this->getMaxSize())
            $this->limitSize($this->getMaxSize());
        elseif ($format === SitemapStyler::GOOGLE_NEWS_FORMAT && count($this->getItems()) > 1000)
            $this->limitSize(1000);
        elseif ($format !== SitemapStyler::GOOGLE_NEWS_FORMAT && count($this->getItems()) > 50000)
            $this->limitSize();

        $data = $this->generate($format, $style);

        return $format === 'html'
            ? $data['content']
            : response($data['content'], 200, $data['headers']);
    }

    /**
     * Generates document with all sitemap items from $items array.
     *
     * @param  string       $format  (options: xml, html, txt, ror-rss, ror-rdf, sitemapindex, google-news)
     * @param  string|null  $style   (path to custom xls style like '/styles/xsl/xml-sitemap.xsl')
     *
     * @return array
     */
    public function generate($format = 'xml', $style = null)
    {
        // check if caching is enabled, there is a cached content and its duration isn't expired
        if ($this->isCached()) {
            $cached = $this->cache->get($this->getCacheKey());
            ($format === SitemapStyler::SITEMAPINDEX_FORMAT)
                ? $this->resetSitemaps($cached)
                : $this->resetItems($cached);
        }
        elseif ($this->isCacheEnabled()) {
            $this->cache->put(
                $this->getCacheKey(),
                $format === SitemapStyler::SITEMAPINDEX_FORMAT ? $this->getSitemaps() : $this->getItems(),
                $this->getCacheDuration()
            );
        }

        if ( ! $this->getLink())
            $this->setLink($this->config->get('app.url'));

        if ( ! $this->getTitle())
            $this->setTitle('SitemapManager for ' . $this->getLink());

        $data = [];

        if ($format === SitemapStyler::SITEMAPINDEX_FORMAT)
            $data['sitemaps'] = $this->getSitemaps();
        else
            $data['items']    = $this->getItems();

        if (in_array($format, ['ror-rss', 'ror-rdf', 'html'])) {
            $data['channel'] = [
                'title' => $this->getTitle(),
                'link'  => $this->getLink(),
            ];
        }

        $this->generator->generate($data, $format, $style);
    }

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
    public function store($format = 'xml', $filename = 'sitemap', $path = null, $style = null)
    {
        // Turn off caching for this method
        $this->setCacheEnabled(false);

        // Use correct file extension
        $extension = in_array($format, ['txt', 'html']) ? $format : 'xml';

        // Use custom size limit for sitemaps
        if (
            $this->getMaxSize() > 0 &&
            count($this->getItems()) >= $this->getMaxSize()
        ) {
            if ($this->getUseLimitSize()) {
                // limit size
                $this->limitSize($this->getMaxSize());
            }
            else {
                // use sitemapindex and generate partial sitemaps
                foreach ($this->getItems()->chunk($this->getMaxSize()) as $key => $item) {
                    // reset current items
                    $this->resetItems($item);

                    // generate new partial sitemap
                    $this->store($format, "$filename-$key", $path, $style);

                    // add sitemap to sitemapindex
                    if ( ! is_null($path))
                        // if using custom path generate relative urls for sitemaps in the sitemapindex
                        $this->addSitemap("$filename-$key.$extension");
                    else
                        // else generate full urls based on app's domain
                        $this->addSitemap(url("$filename-$key.$extension"));
                }

                $format = SitemapStyler::SITEMAPINDEX_FORMAT;
            }
        }
        elseif (
            ($format !== SitemapStyler::GOOGLE_NEWS_FORMAT && count($this->getItems()) > 50000) ||
            ($format === SitemapStyler::GOOGLE_NEWS_FORMAT && count($this->getItems()) > 1000)
        ) {
            ($format !== SitemapStyler::GOOGLE_NEWS_FORMAT) ? $max = 50000 : $max = 1000;

            // check if limiting size of items array is enabled
            if ( ! $this->getUseLimitSize()) {
                // use sitemapindex and generate partial sitemaps
                foreach ($this->getItems()->chunk($max) as $key => $item) {
                    // reset current items
                    $this->resetItems($item);

                    // generate new partial sitemap
                    $this->store($format, "$filename-$key", $path, $style);

                    // add sitemap to sitemapindex
                    if ( ! is_null($path))
                        // if using custom path generate relative urls for sitemaps in the sitemapindex
                        $this->addSitemap("$filename-$key.$extension");
                    else
                        // else generate full urls based on app's domain
                        $this->addSitemap(url("$filename-$key.$extension"));
                }

                $format = SitemapStyler::SITEMAPINDEX_FORMAT;
            }
            else {
                // reset items and use only most recent $max items
                $this->limitSize($max);
            }
        }

        $data = $this->generate($format, $style);

        // if custom path
        $file = $path == null
            ? public_path("$filename.$extension")
            : $path . DS . "$filename.$extension";

        // must return something
        return $this->filesystem->put($file, $data['content']);
    }

    /**
     * Reset the sitemaps container.
     *
     * @param  array  $sitemaps
     *
     * @return self
     */
    public function resetSitemaps(array $sitemaps = [])
    {
        $this->sitemaps = $sitemaps;

        return $this;
    }

    /**
     * Reset the items array.
     *
     * @param  array  $items
     *
     * @return self
     */
    public function resetItems(array $items = [])
    {
        $this->items = new Entities\SitemapItemCollection($items);

        return $this;
    }
}
