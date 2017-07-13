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
            <title>{{ $url->getTitle() }}</title>

            @unless (empty($url->loc()))
            <link>{{ $url->loc() }}</link>
            @endunless

            @unless (empty($url->lastMod()))
            <ror:updated>{{ $url->formatLastMod() }}</ror:updated>
            @endunless

            @unless (empty($url->changeFreq()))
            <ror:updatePeriod>{{ $url->changeFreq() }}</ror:updatePeriod>
            @endunless

            @unless (empty($url->priority()))
            <ror:sortOrder>{{ $url->priority() }}</ror:sortOrder>
            @endunless

            <ror:resourceOf>sitemap</ror:resourceOf>
        </item>
        @endforeach
    </channel>
</rss>
