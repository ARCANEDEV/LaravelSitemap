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
            @if ($url->has('loc'))
            <loc>{{ $url->loc() }}</loc>
            @endif

            @if ($url->has('lastmod'))
            <lastmod>{{ $url->formatLastMod() }}</lastmod>
            @endif

            @if ($url->has('changefreq'))
            <changefreq>{{ $url->changeFreq() }}</changefreq>
            @endif

            @if ($url->has('priority'))
            <priority>{{ $url->priority() }}</priority>
            @endif
        </url>
    @endforeach
</urlset>
