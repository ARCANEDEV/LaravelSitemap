<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:ror="http://rorweb.com/0.1/" >
    <channel>
        <title>{{ $channel['title'] }}</title>
        <link>{{ $channel['link'] }}</link>
        @foreach($items as $item)
        <item>
            <link>{{ $item->getLoc() }}</link>
            <title>{{ $item->getTitle() }}</title>
            <ror:updated>{{ $item->getLastmod() }}</ror:updated>
            <ror:updatePeriod>{{ $item->getFreq() }}</ror:updatePeriod>
            <ror:sortOrder>{{ $item->getPriority() }}</ror:sortOrder>
            <ror:resourceOf>sitemap</ror:resourceOf>
        </item>
        @endforeach
    </channel>
</rss>
