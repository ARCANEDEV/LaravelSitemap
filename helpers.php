<?php

if ( ! function_exists('sitemap')) {
    /**
     * Get the Sitemap Manager instance.
     *
     * @return \Arcanedev\LaravelSitemap\Contracts\SitemapManager
     */
    function sitemap()
    {
        return app(\Arcanedev\LaravelSitemap\Contracts\SitemapManager::class);
    }
}
