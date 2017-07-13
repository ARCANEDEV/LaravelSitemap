<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <?php
        /**
         * @var  \Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap  $sitemap
         * @var  \Arcanedev\LaravelSitemap\Contracts\Entities\Url      $url
         */
    ?>
    @foreach($sitemap->getUrls() as $url)
        <url>
            @unless (empty($url->loc()))
            <loc>{{ $url->loc() }}</loc>
            @endunless

            @unless (empty($url->lastMod()))
            <lastmod>{{ $url->formatLastMod() }}</lastmod>
            @endunless

            @unless (empty($url->changeFreq()))
            <changefreq>{{ $url->changeFreq() }}</changefreq>
            @endunless

            @unless (empty($url->priority()))
            <priority>{{ $url->priority() }}</priority>
            @endunless
        </url>
    @endforeach
</urlset>
