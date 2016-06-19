<?php namespace Arcanedev\LaravelSitemap;

use Arcanedev\Support\PackageServiceProvider as ServiceProvider;

/**
 * Class     LaravelSitemapServiceProvider
 *
 * @package  Arcanedev\LaravelSitemap
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LaravelSitemapServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'sitemap';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer   = true;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the base path of the package.
     *
     * @return string
     */
    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerSitemapStyler();
        $this->registerSitemapGenerator();
        $this->registerSitemapManager();
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->publishConfig();
        $this->publishViews();
        $this->publishTranslations();

        $this->publishes([
            $this->getBasePath() . '/public' => public_path(Helpers\SitemapStyler::VENDOR_PATH)
        ], 'public');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'sitemap.manager',
            Contracts\SitemapManager::class,
            'sitemap.styler',
            Contracts\SitemapStyler::class,
            'sitemap.generator',
            Contracts\SitemapGenerator::class,
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the sitemap styler.
     */
    private function registerSitemapStyler()
    {
        $this->app->bind('sitemap.styler', Helpers\SitemapStyler::class);
        $this->bind(Contracts\SitemapStyler::class, 'sitemap.styler');
    }

    /**
     * Register the sitemap generator.
     */
    private function registerSitemapGenerator()
    {
        $this->app->bind('sitemap.generator', Helpers\SitemapGenerator::class);
        $this->bind(Contracts\SitemapGenerator::class, 'sitemap.generator');
    }

    /**
     * Register the sitemap manager.
     */
    private function registerSitemapManager()
    {
        $this->app->bind('sitemap.manager', function ($app) {
            /**
             * @var  \Illuminate\Contracts\Config\Repository               $config
             * @var  \Illuminate\Cache\CacheManager                        $cache
             * @var  \Illuminate\Filesystem\Filesystem                     $filesystem
             * @var  \Arcanedev\LaravelSitemap\Contracts\SitemapGenerator  $generator
             */
            $cache      = $app['cache'];
            $config     = $app['config'];
            $filesystem = $app['files'];
            $generator  = $app['sitemap.generator'];

            return new SitemapManager(
                $cache->driver(),
                $config,
                $filesystem,
                $generator
            );
        });
        $this->bind(Contracts\SitemapManager::class, 'sitemap.manager');
    }
}
