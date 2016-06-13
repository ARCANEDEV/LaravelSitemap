<!DOCTYPE html>
<html>
<head>
    <title>{{ $channel['title'] }}</title>
</head>
<body>
    <h1><a href="{{ $channel['link'] }}">{{ $channel['title'] }}</a></h1>
    <ul>
        @foreach($items as $item)
            <li>
                <a href="{{ $item->getLoc() }}">{{ empty($item->getTitle()) ? $item->getLoc() : $item->getTitle() }}</a>
                <small>(last updated: {{ $item->getLastmod() }})</small>
            </li>
        @endforeach
    </ul>
</body>
</html>
