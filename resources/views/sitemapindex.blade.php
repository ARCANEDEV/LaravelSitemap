<?xml version="1.0" encoding="UTF-8"?>
@if ($style)
    <?xml-stylesheet href="{{ $style }}" type="text/xsl"?>
@endif
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($sitemaps as $sitemap)
    <sitemap>
        <loc>{{ $sitemap['loc'] }}</loc>
        @if ($sitemap['lastmod'])
        <lastmod>{{ date('Y-m-d\TH:i:sP', strtotime($sitemap['lastmod'])) }}</lastmod>
        @endif
    </sitemap>
@endforeach
</sitemapindex>
