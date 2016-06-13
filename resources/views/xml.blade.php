<?xml version="1.0" encoding="UTF-8"?>
@if ($style)
    <?xml-stylesheet href="{{ $style }}" type="text/xsl"?>
@endif
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
    @foreach ($items as $item)
        <url>
            <loc>{{ $item->getLoc() }}</loc>
            @if (count($item->getTranslations()))
                @foreach ($item->getTranslations() as $translation)
                    <xhtml:link rel="alternate" hreflang="{{ $translation['language'] }}" href="{{ $translation['url'] }}" />
                @endforeach
            @endif
            @if (count($item->getAlternates())) {
                @foreach ($item->getAlternates() as $alternate)
                    <xhtml:link rel="alternate" media="{{ $alternate['media'] }}" href="{{ $alternate['url'] }}" />
                @endforeach
            @endif
            @if ($item->getPriority())
                <priority>{{ $item->getPriority() }}</priority>
            @endif
            @if ($item->getLastmod())
                <lastmod>{{ $item->getLastmod() }}</lastmod>
            @endif

            @if ($item->getFreq())
                <changefreq>{{ $item->getFreq() }}</changefreq>
            @endif

            @if (count($item->getImages()))
                @foreach ($item->getImages() as $image)
                    <image:image>
                        <image:loc>{{ $image['url'] }}</image:loc>
                        @if (isset($image['title']))
                            <image:title>{{ $image['title'] }}</image:title>
                        @endif

                        @if (isset($image['caption']))
                            <image:caption>{{ $image['caption'] }}</image:caption>
                        @endif

                        @if (isset($image['geo_location']))
                            <image:geo_location>{{ $image['geo_location'] }}</image:geo_location>
                        @endif

                        @if (isset($image['license']))
                            <image:license>{{ $image['license'] }}</image:license>
                        @endif
                    </image:image>
                @endforeach
            @endif

            @if (count($item->getVideos()))
                @foreach ($item->getVideos() as $video) {
                <video:video>
                    @if (isset($video['thumbnail_loc']))
                        <video:thumbnail_loc>{{ $video['thumbnail_loc'] }}</video:thumbnail_loc>
                    @endif
                    @if (isset($video['title']))
                        <video:title><![CDATA[{{ $video['title'] }}]]></video:title>
                    @endif
                    @if (isset($video['description']))
                        <video:description><![CDATA[{{ $video['description'] }}]]></video:description>
                    @endif
                    @if (isset($video['content_loc']))
                        <video:content_loc>{{ $video['content_loc'] }}</video:content_loc>
                    @endif
                    @if (isset($video['duration']))
                        <video:duration>{{ $video['duration'] }}</video:duration>
                    @endif
                    @if (isset($video['expiration_date']))
                        <video:expiration_date>{{ $video['expiration_date'] }}</video:expiration_date>
                    @endif
                    @if (isset($video['rating']))
                        <video:rating>{{ $video['rating'] }}</video:rating>
                    @endif
                    @if (isset($video['view_count']))
                        <video:view_count>{{ $video['view_count'] }}</video:view_count>
                    @endif
                    @if (isset($video['publication_date']))
                        <video:publication_date>{{ $video['publication_date'] }}</video:publication_date>
                    @endif
                    @if (isset($video['family_friendly']))
                        <video:family_friendly>{{ $video['family_friendly'] }}</video:family_friendly>
                    @endif
                    @if (isset($video['requires_subscription']))
                        <video:requires_subscription>{{ $video['requires_subscription'] }}</video:requires_subscription>
                    @endif
                    @if (isset($video['live']))
                        <video:live>{{ $video['live'] }}</video:live>
                    @endif
                    @if (isset($video['player_loc']))
                        <video:player_loc allow_embed="{{ $video['player_loc']['allow_embed'] }}" autoplay="{{ $video['player_loc']['autoplay'] }}">{{ $video['player_loc']['player_loc'] }}</video:player_loc>
                    @endif
                    @if (isset($video['restriction']))
                        <video:restriction relationship="{{ $video['restriction']['relationship'] }}">{{ $video['restriction']['restriction'] }}</video:restriction>
                    @endif
                    @if (isset($video['gallery_loc']))
                        <video:gallery_loc title="{{ $video['gallery_loc']['title'] }}">{{ $video['gallery_loc']['gallery_loc'] }}</video:gallery_loc>
                    @endif
                    @if (isset($video['price']))
                        <video:price currency="{{ $video['price']['currency'] }}">{{ $video['price']['price'] }}</video:price>
                    @endif
                    @if (isset($video['uploader']))
                        <video:uploader info="{{ $video['uploader'] }}">{{ $video['uploader']['uploader'] }}</video:uploader>
                    @endif
                </video:video>
                @endforeach
            @endif
        </url>
    @endforeach
</urlset>
