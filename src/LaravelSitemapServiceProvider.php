<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSitemap;

use Arcanedev\LaravelSitemap\Contracts\SitemapManager as SitemapManagerContract;
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

        $this->singleton(SitemapManagerContract::class, SitemapManager::class);
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
            SitemapManagerContract::class,
        ];
    }
}
