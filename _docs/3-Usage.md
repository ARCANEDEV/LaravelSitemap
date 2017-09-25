# 3. Usage

## Table of contents

 1. [Installation and Setup](1-Installation-and-Setup.md)
 2. [Configuration](2-Configuration.md)
 3. [Usage](3-Usage.md)
    * [Entities](#entities)
    * [Manager](#manager)

## Entities

### Url Entity

This entity allows to manipulate an `Url` attribute with ease.
 
#### API

All available methods are available with the `Arcanedev\LaravelSitemap\Contracts\Entities\Url` interface:
 
> **Note :** The `Arcanedev\LaravelSitemap\Entities\Url` class extends from `Illuminate\Support\Fluent` class and implements the `Illuminate\Contracts\Support\Arrayable`, `Illuminate\Contracts\Support\Jsonable`, `JsonSerializable` interfaces.

```php 
/* -----------------------------------------------------------------
 |  Getters & Setters
 | -----------------------------------------------------------------
 */

/**
 * Get the url location.
 *
 * @return string
 */
public function getLoc();

/**
 * Set the url location.
 *
 * @param  string  $loc
 *
 * @return self
 */
public function setLoc($loc);

/**
 * Get the last modification date.
 *
 * @return \DateTimeInterface
 */
public function getLastMod();

/**
 * Format the url last modification.
 *
 * @param  string  $format
 *
 * @return string
 */
public function formatLastMod($format = DateTime::ATOM);

/**
 * Set the last modification date.
 *
 * @param  string|\DateTimeInterface  $lastModDate
 * @param  string                     $format
 *
 * @return self
 */
public function setLastMod($lastModDate, $format = 'Y-m-d H:i:s');

/**
 * Get the change frequency.
 *
 * @return string
 */
public function getChangeFreq();

/**
 * Set the change frequency.
 *
 * @param  string  $changeFreq
 *
 * @return self
 */
public function setChangeFreq($changeFreq);

/**
 * Get the priority.
 *
 * @return float
 */
public function getPriority();

/**
 * Set the priority.
 *
 * @param  float  $priority
 *
 * @return self
 */
public function setPriority($priority);

/**
 * Get the title.
 *
 * @return string|null
 */
public function getTitle();

/**
 * Get the title.
 *
 * @param  string  $title
 *
 * @return self
 */
public function setTitle($title);

/**
 * Get an attribute from the container.
 *
 * @param  string  $key
 * @param  mixed   $default
 *
 * @return mixed
 */
public function get($key, $default = null);

/* -----------------------------------------------------------------
 |  Main Methods
 | -----------------------------------------------------------------
 */

/**
 * Create a sitemap url instance.
 *
 * @param  string  $loc
 *
 * @return \Arcanedev\LaravelSitemap\Entities\Url
 */
public static function make($loc);

/**
 * Make a URL instance with attributes.
 *
 * @param  array  $attributes
 *
 * @return \Arcanedev\LaravelSitemap\Entities\Url
 */
public static function makeFromArray(array $attributes);

/**
 * Check if has an attribute.
 *
 * @param  string  $key
 *
 * @return bool
 */
public function has($key);
```

There are 3 ways to initiate a URL instance.

Keep in mind that `loc`, `lastmod`, `changefreq`, `priority`, `title` attributes are the *essential* parts for the URL Entity but only the `loc` attribute is **required**.

```php
use Arcanedev\LaravelSitemap\Entities\Url;

// Example #1
$url = new Url([
    'loc'        => 'http://example.com',
    'lastmod'    => date('Y-m-d H:i:s'), // You can also pass a DateTime object.
    'changefreq' => 'daily',
    'priority'   => 1.0,
    'title'      => 'Homepage - Example',
]);

// Example #2
$url = Url::makeFromArray([
    'loc'        => 'http://example.com',
    'lastmod'    => date('Y-m-d H:i:s'), // You can also pass a DateTime object.
    'changefreq' => 'daily',
    'priority'   => 1.0,
    'title'      => 'Homepage - Example',
]);

// Example #3

$url = Url::make('http://example.com')
          ->setLastMod(date('Y-m-d H:i:s'))
          ->setChangeFreq('daily')
          ->setPriority(1.0)
          ->setTitle('Homepage - Example');
``` 

To learn more about the entity, you can check the [Url Test file](https://github.com/ARCANEDEV/LaravelSitemap/blob/master/tests/Entities/UrlTest.php).

### Sitemap Entity

This entity manage the `Url` collection and its related data.

#### API

```php
/* -----------------------------------------------------------------
 |  Getters & Setters
 | -----------------------------------------------------------------
 */

/**
 * Set the sitemap path.
 *
 * @param  string  $path
 *
 * @return self
 */
public function setPath($path);

/**
 * Get the sitemap path.
 *
 * @return string|null
 */
public function getPath();

/**
 * Get the sitemap's URLs.
 *
 * @return \Illuminate\Support\Collection
 */
public function getUrls();

/**
 * Set the URLs Collection.
 *
 * @param  \Illuminate\Support\Collection  $urls
 *
 * @return self
 */
public function setUrls(Collection $urls);

/* -----------------------------------------------------------------
 |  Main Methods
 | -----------------------------------------------------------------
 */

/**
 * Get a URL instance by its loc.
 *
 * @param  string      $loc
 * @param  mixed|null  $default
 *
 * @return \Arcanedev\LaravelSitemap\Entities\Url|null
 */
public function getUrl($loc, $default = null);

/**
 * Add a sitemap URL to the collection.
 *
 * @param  \Arcanedev\LaravelSitemap\Contracts\Entities\Url  $url
 *
 * @return $this
 */
public function add(Url $url);

/**
 * Add many urls to the collection.
 *
 * @param  array  $urls
 *
 * @return self
 */
public function addMany($urls);

/**
 * Create and Add a sitemap URL to the collection.
 *
 * @param  string    $loc
 * @param  callable  $callback
 *
 * @return self
 */
public function create($loc, callable $callback);

/**
 * Check if the url exists in the sitemap items.
 *
 * @param  string  $url
 *
 * @return bool
 */
public function has($url);

/**
 * Check if the number of URLs is exceeded.
 *
 * @return bool
 */
public function isExceeded();

/**
 * Chunk the sitemap to multiple chunks if the size is exceeded.
 *
 * @return \Illuminate\Support\Collection
 */
public function chunk();
```

#### Examples

The way to start building your `sitemap` is pretty straightforward:

```php

use Arcanedev\LaravelSitemap\Entities\Sitemap;

// Classic way:

$map = new Sitemap;
$map->setPath('http://example.com/sitemap-pages.xml');

// Zonda way:

$map = Sitemap::make()->setPath('http://example.com/sitemap-pages.xml');
```

Now lets add some urls:

```php
use Arcanedev\LaravelSitemap\Entities\Sitemap;
use Arcanedev\LaravelSitemap\Entities\Url;

$map = Sitemap::make()->setPath('http://example.com/sitemap-pages.xml');

// Example #1

$map->create('http://example.com', function (Url $url) { 
    $url->setLastMod(date('Y-m-d H:i:s'))
        ->setChangeFreq('daily')
        ->setPriority(1.0)
        ->setTitle('Homepage - Example');
});

// Note: For the `Url` typehint, you can use the contract `Arcanedev\LaravelSitemap\Contracts\Entities\Url` or remove it completely.

// Example #2
$map->add(
    Url::make('http://example.com/contact')
       ->setLastMod(date('Y-m-d H:i:s'))
       ->setChangeFreq('monthly')
       ->setPriority(5.0)
       ->setTitle('Homepage - Example');
);

// Example #3
$posts = \App\Models\Post::all();  

// The `$posts` variable is an Eloquent Collection but it also works with the basic one (\Illuminate\Support\Collection).
$map->addMany($posts->map(function ($post) {
    return Url::make(route('blog.posts.show', [$post]))
        ->setLastMod($post->updated_at)
        ->setChangeFreq('weekly')
        ->setPriority(4.6)
        ->setTitle($post->title);
});

// You can also pass an array of `Arcanedev\Laravel\Entities\Url` or `Arcanedev\Laravel\Contracts\Entities\Url` objects.

$urls = [
    Url::make('...'),
    Url::make('...'),
    // ...
];

$map->addMany($urls);
```

Check the [Sitemap Test File](https://github.com/ARCANEDEV/LaravelSitemap/blob/master/tests/Entities/SitemapTest.php) for more details about the usage.

## Manager

The `SitemapManager` allows you to manage multiple sitemaps and can render/save, it can also respond with an Http response object (building sitemaps on the fly).

#### API

```php
/* -----------------------------------------------------------------
 |  Getters & Setters
 | -----------------------------------------------------------------
 */

/**
 * Set the format.
 *
 * @param  string  $format
 *
 * @return self
 */
public function format($format);

/* -----------------------------------------------------------------
 |  Main Methods
 | -----------------------------------------------------------------
 */

/**
 * Create and add a sitemap to the collection.
 *
 * @param  string    $name
 * @param  callable  $callback
 *
 * @return self
 */
public function create($name, callable $callback);

/**
 * Add a sitemap to the collection.
 *
 * @param  string                                                $name
 * @param  \Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap  $sitemap
 *
 * @return self
 */
public function add($name, Sitemap $sitemap);

/**
 * Get the sitemaps collection.
 *
 * @return \Illuminate\Support\Collection
 */
public function all();

/**
 * Get a sitemap instance.
 *
 * @param  string      $name
 * @param  mixed|null  $default
 *
 * @return \Arcanedev\LaravelSitemap\Entities\Sitemap|null
 */
public function get($name, $default = null);

/**
 * Check if a sitemap exists.
 *
 * @param  string  $name
 *
 * @return bool
 */
public function has($name);

/**
 * Remove a sitemap from the collection by key.
 *
 * @param  string|array  $names
 *
 * @return self
 */
public function forget($names);

/**
 * Render the sitemaps.
 *
 * @param  string|null  $name
 *
 * @return string|null
 */
public function render($name = null);

/**
 * Save the sitemaps.
 *
 * @param  string       $path
 * @param  string|null  $name
 *
 * @return self
 */
public function save($path, $name = null);

/**
 * Render the Http response.
 *
 * @param  string  $name
 * @param  int     $status
 * @param  array   $headers
 *
 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
 */
public function respond($name = null, $status = 200, array $headers = []);
```

#### Examples

Please check the [SitemapManager Test File](https://github.com/ARCANEDEV/LaravelSitemap/blob/master/tests/SitemapManagerTest.php) for more details.
