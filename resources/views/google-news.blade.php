<?xml version="1.0" encoding="UTF-8"?>
@if ($style)
<?xml-stylesheet href="{{ $style }}" type="text/xsl"?>
@endif
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
    @foreach ($items as $item)
    <?php $googlenews = $item->getGooglenews() ?>
    <url>
        <loc>{{ $item->getLoc() }}</loc>
        @if ($item->getLastmod())
            <lastmod>{{ $item->getLastmod() }}</lastmod>
        @endif
        @if ( ! empty($item['alternates']))
            @foreach ($item['alternates'] as $alternate)
            <xhtml:link rel="alternate" media="{{ $alternate['media'] }}" href="{{ $alternate['url'] }}" />
            @endforeach
        @endif
        <news:news>
            <news:publication>
                <news:name>{{ $googlenews['sitename'] }}</news:name>
                <news:language>{{ $googlenews['language'] }}</news:language>
            </news:publication>
            <news:publication_date>{{ date('Y-m-d\TH:i:sP', strtotime($googlenews['publication_date'])) }}</news:publication_date>
            <news:title>{{ $item->getTitle() }}</news:title>
            @if (isset($googlenews['access']))
            <news:access>{{ $googlenews['access'] }}</news:access>
            @endif

            @if (isset($googlenews['keywords']))
            <news:keywords>{{ $googlenews['keywords'] }}</news:keywords>
            @endif

            @if (isset($googlenews['genres']))
            <news:genres>{{ implode(',', $googlenews['genres']) }}</news:genres>
            @endif

            @if (isset($googlenews['stock_tickers']))
            <news:stock_tickers>{{ implode(',', $googlenews['stock_tickers']) }}</news:stock_tickers>
            @endif
        </news:news>
    </url>
    @endforeach
</urlset>
