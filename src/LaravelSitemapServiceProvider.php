<?php namespace Arcanedev\LaravelSitemap;

use Arcanedev\Support\Providers\PackageServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class     LaravelSitemapServiceProvider
 *
 * @package  Arcanedev\LaravelSitemap
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LaravelSitemapServiceProvider extends PackageServiceProvider implements DeferrableProvider
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

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->registerConfig();

        $this->singleton(Contracts\SitemapManager::class, SitemapManager::class);
    }


    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        $this->publishConfig();
        $this->publishViews();
        $this->publishTranslations();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            Contracts\SitemapManager::class,
        ];
    }
}
