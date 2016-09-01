<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rdf:RDF xmlns="http://rorweb.com/0.1/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
    <Resource rdf:about="sitemap">
        <title>{{ $channel['title'] }}</title>
        <url>{{ $channel['link'] }}</url>
        <type>sitemap</type>
    </Resource>
    @foreach ($items as $item)
    <Resource>
        <url>{{ $item->getLoc() }}</url>
        <title>{{ $item->getTitle() }}</title>
        <updated>{{ $item->getLastmod() }}</updated>
        <updatePeriod>{{ $item->getFreq() }}</updatePeriod>
        <sortOrder>{{ $item->getPriority() }}</sortOrder>
        <resourceOf rdf:resource="sitemap"/>
    </Resource>
    @endforeach
</rdf:RDF>
