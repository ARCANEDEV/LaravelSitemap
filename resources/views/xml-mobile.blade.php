<?xml version="1.0" encoding="UTF-8"?>
@if ($style)
    <?xml-stylesheet href="{{ $style }}" type="text/xsl"?>
@endif
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">
@foreach($items as $item)
  <url>
    <loc>{{ $item->getLoc() }}</loc>
    <mobile:mobile/>
  </url>
@endforeach
</urlset>
