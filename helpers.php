<?php

use Arcanedev\LaravelSitemap\Contracts\SitemapManager;

if ( ! function_exists('sitemap')) {
    /**
     * Get the Sitemap Manager instance.
     *
     * @return SitemapManager
     */
    function sitemap() {
        return app(SitemapManager::class);
    }
}
