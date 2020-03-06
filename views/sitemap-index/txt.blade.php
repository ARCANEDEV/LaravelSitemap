@foreach($sitemaps as $name => $sitemap)
{{ $sitemap->getPath() }}
@endforeach
