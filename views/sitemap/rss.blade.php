<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:ror="http://rorweb.com/0.1/">
    <channel>
        <title>XML Sitemaps RSS Feed</title>
        <link>{{ $sitemap->getPath() }}</link>
        <?php
        /**
         * @var  \Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap  $sitemap
         * @var  \Arcanedev\LaravelSitemap\Contracts\Entities\Url      $url
         */
        ?>
        @foreach($sitemap->getUrls() as $url)
        <item>
            @if ($url->has('title'))
            <title>{{ $url->getTitle() }}</title>
            @endif

            @if ($url->has('loc'))
            <link>{{ $url->getLoc() }}</link>
            @endif

            @if ($url->has('lastmod'))
            <ror:updated>{{ $url->formatLastMod() }}</ror:updated>
            @endif

            @if ($url->has('changefreq'))
            <ror:updatePeriod>{{ $url->getChangeFreq() }}</ror:updatePeriod>
            @endif

            @if ($url->has('priority'))
            <ror:sortOrder>{{ $url->getPriority() }}</ror:sortOrder>
            @endif

            <ror:resourceOf>sitemap</ror:resourceOf>
        </item>
        @endforeach
    </channel>
</rss>
