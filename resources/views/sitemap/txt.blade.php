<?php
/**
 * @var  \Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap  $sitemap
 * @var  \Arcanedev\LaravelSitemap\Contracts\Entities\Url      $url
 */
?>
@foreach($sitemap->getUrls() as $url)
{{ $url->loc() }}
@endforeach
