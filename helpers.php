<?php

use Arcanedev\LaravelSitemap\Contracts\SitemapManager;

if ( ! function_exists('sitemap')) {
    /**
     * Get the Sitemap Manager instance.
     *
     * @return \Arcanedev\LaravelSitemap\Contracts\SitemapManager
     */
    function sitemap(): SitemapManager {
        return app(SitemapManager::class);
    }
}
