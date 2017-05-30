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
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
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

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();

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
        parent::boot();

        $this->publishConfig();
        $this->publishViews();
        $this->publishTranslations();

        $this->publishes([
            $this->getBasePath().'/public' => public_path(Helpers\SitemapStyler::VENDOR_PATH)
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
            Contracts\SitemapManager::class,
            Contracts\SitemapStyler::class,
            Contracts\SitemapGenerator::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the sitemap styler.
     */
    private function registerSitemapStyler()
    {
        $this->bind(Contracts\SitemapStyler::class, Helpers\SitemapStyler::class);
    }

    /**
     * Register the sitemap generator.
     */
    private function registerSitemapGenerator()
    {
        $this->bind(Contracts\SitemapGenerator::class, Helpers\SitemapGenerator::class);
    }

    /**
     * Register the sitemap manager.
     */
    private function registerSitemapManager()
    {
        $this->app->bind(Contracts\SitemapManager::class, function ($app) {
            /**
             * @var  \Illuminate\Contracts\Config\Repository               $config
             * @var  \Illuminate\Contracts\Cache\Factory                   $cache
             * @var  \Illuminate\Filesystem\Filesystem                     $filesystem
             * @var  \Arcanedev\LaravelSitemap\Contracts\SitemapGenerator  $generator
             */
            $cache      = $app['cache'];
            $config     = $app['config'];
            $filesystem = $app['files'];
            $generator  = $app[Contracts\SitemapGenerator::class];

            return new SitemapManager(
                $cache->store(),
                $config,
                $filesystem,
                $generator
            );
        });
    }
}
