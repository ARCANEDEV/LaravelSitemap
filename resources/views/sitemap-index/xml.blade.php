<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd"
              xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php /** @var  \Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap $sitemap */ ?>
    @foreach($sitemaps as $name => $sitemap)
    <sitemap>
        @if (! empty($name))
        <loc>{{ $sitemap->getPath() }}</loc>
        @endif

        <?php
        /** @var  \Arcanedev\LaravelSitemap\Contracts\Entities\Url $latest */
        $latest = $sitemap->getUrls()->last(function (\Arcanedev\LaravelSitemap\Contracts\Entities\Url $url) {
        return $url->getLastMod();
        });
        ?>
        @unless (is_null($latest))
        <lastmod>{{ $latest->getLastMod()->format(DateTime::ATOM) }}</lastmod>
        @endunless
    </sitemap>
    @endforeach
</sitemapindex>
