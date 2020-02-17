<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSitemap\Tests\Entities;

use Arcanedev\LaravelSitemap\Contracts\Entities\ChangeFrequency;
use Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap as SitemapContract;
use DateTime;
use Arcanedev\LaravelSitemap\Entities\{Sitemap, Url};
use Arcanedev\LaravelSitemap\Tests\TestCase;
use Countable;
use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use JsonSerializable;
use Spatie\Snapshots\MatchesSnapshots;

/**
 * Class     SitemapTest
 *
 * @package  Arcanedev\LaravelSitemap\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use MatchesSnapshots;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelSitemap\Entities\Sitemap */
    private $sitemap;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    protected function setUp(): void
    {
        parent::setUp();

        $this->sitemap = new Sitemap;
    }

    protected function tearDown(): void
    {
        unset($this->sitemap);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $expectations = [
            Countable::class,
            JsonSerializable::class,
            Arrayable::class,
            Jsonable::class,
            SitemapContract::class,
            Sitemap::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->sitemap);
        }

        static::assertSame(0, $this->sitemap->count());
    }

    /** @test */
    public function it_can_make(): void
    {
        $map = Sitemap::make();

        $expectations = [
            Countable::class,
            JsonSerializable::class,
            Arrayable::class,
            Jsonable::class,
            SitemapContract::class,
            Sitemap::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $map);
        }

        static::assertSame(0, $map->count());
    }

    /** @test */
    public function it_can_add_url_to_the_collection(): void
    {
        static::assertSame(0, $this->sitemap->count());

        $this->sitemap->add(static::createUrlSample());

        static::assertSame(1, $this->sitemap->count());
    }

    /** @test */
    public function it_can_create_and_add_url_to_collection(): void
    {
        static::assertSame(0, $this->sitemap->count());

        $this->sitemap->create('http://example.com', function (Url $url) {
            $url->setChangeFreq(ChangeFrequency::ALWAYS)
                ->setPriority(1.0)
                ->setLastMod('2017-01-01 00:00:00')
                ->setTitle('Example - Homepage');
        });

        static::assertSame(1, $this->sitemap->count());

        /** @var \Arcanedev\LaravelSitemap\Entities\Url $url */
        $url = $this->sitemap->getUrls()->last();

        static::assertSame('http://example.com', $url->getLoc());
        static::assertSame(ChangeFrequency::ALWAYS, $url->getChangeFreq());
        static::assertSame(1.0, $url->getPriority());
        static::assertSame('2017-01-01 00:00:00', $url->getLastMod()->format('Y-m-d H:i:s'));
        static::assertSame('Example - Homepage', $url->getTitle());
    }

    /** @test */
    public function it_can_check_if_has_an_existing_url(): void
    {
        $url = static::createUrlSample();

        static::assertFalse($this->sitemap->has($url->getLoc()));

        $this->sitemap->add($url);

        static::assertTrue($this->sitemap->has($url->getLoc()));
    }

    /** @test */
    public function it_can_get_url_by_its_loc(): void
    {
        $url = static::createUrlSample();

        static::assertNull($this->sitemap->getUrl($url->getLoc()));

        $this->sitemap->add($url);

        static::assertSame($url, $this->sitemap->getUrl($url->getLoc()));
    }

    /** @test */
    public function it_can_convert_to_array(): void
    {
        static::assertSame([], $this->sitemap->toArray());

        $this->sitemap->add(static::createUrlSample());

        $expected = [
            [
                'loc'        => 'http://example.com',
                'lastmod'    => '2017-01-01T00:00:00+00:00',
                'changefreq' => 'always',
                'priority'   => 1.0,
                'title'      => 'Example - Homepage',
            ]
        ];

        static::assertSame($expected, $this->sitemap->toArray());
    }

    /** @test */
    public function it_can_convert_to_json(): void
    {
        $expected = '[]';

        static::assertSame($expected, json_encode($this->sitemap));
        static::assertSame($expected, $this->sitemap->toJson());

        $this->sitemap->add(static::createUrlSample());

        $expected = json_encode($this->sitemap->toArray());

        static::assertSame($expected, json_encode($this->sitemap));
        static::assertSame($expected, $this->sitemap->toJson());
    }

    /** @test */
    public function it_should_treats_urls_as_the_basic_collection_class(): void
    {
        $this->sitemap = $this->createBlogSitemap();

        static::assertMatchesSnapshot(
            $this->sitemap->getUrls()->pluck('title', 'loc')->toArray()
        );
    }

    /** @test */
    public function it_can_check_if_number_of_urls_is_exceeded(): void
    {
        // Max is 500 for tests

        $sitemap = $this->createBlogSitemap();

        static::assertSame(11, $sitemap->count());
        static::assertFalse($this->createBlogSitemap()->isExceeded());

        $sitemap = $this->createBlogSitemap(498);

        static::assertSame(499, $sitemap->count()); // 1 blog index + 498 posts = 499 urls
        static::assertTrue($sitemap->isExceeded());

        $sitemap = $this->createBlogSitemap(499);

        static::assertSame(500, $sitemap->count()); // 1 blog index + 499 posts = 500 urls
        static::assertTrue($sitemap->isExceeded());
    }

    /** @test */
    public function it_can_set_urls(): void
    {
        $this->sitemap->add(static::createUrlSample());

        static::assertSame(1, $this->sitemap->count());

        $urls = collect([
            ['loc' => 'http://example.com'],
            ['loc' => 'http://example.com/about-us'],
            ['loc' => 'http://example.com/contact'],
        ]);

        $now = new DateTime;

        $this->sitemap->setUrls($urls->transform(function (array $item) use ($now) {
            return Url::makeFromArray($item)->setLastMod($now);
        }));

        static::assertSame(3, $this->sitemap->count());

        $formattedDate = $now->format(DateTime::ATOM);

        $expected = [
            [
                'loc'        => 'http://example.com',
                'lastmod'    => $formattedDate,
                'changefreq' => 'daily',
                'priority'   => 0.8,
                'title'      => null,
            ],[
                'loc'        => 'http://example.com/about-us',
                'lastmod'    => $formattedDate,
                'changefreq' => 'daily',
                'priority'   => 0.8,
                'title'      => null,
            ],[
                'loc'        => 'http://example.com/contact',
                'lastmod'    => $formattedDate,
                'changefreq' => 'daily',
                'priority'   => 0.8,
                'title'      => null,
            ],
        ];

        static::assertSame($expected, $this->sitemap->toArray());
    }

    /** @test */
    public function it_can_add_many_urls_to_the_collection(): void
    {
        $this->sitemap->add(static::createUrlSample());

        static::assertSame(1, $this->sitemap->count());

        $urls = collect([
            ['loc' => 'http://example.com/news'],
            ['loc' => 'http://example.com/about-us'],
            ['loc' => 'http://example.com/contact'],
        ]);

        $now = new DateTime;

        $this->sitemap->addMany($urls->transform(function (array $item) use ($now) {
            return Url::makeFromArray($item)->setLastMod($now);
        }));

        static::assertSame(4, $this->sitemap->count());

        $formattedDate = $now->format(DateTime::ATOM);

        $expected = [
            [
                'loc'        => 'http://example.com',
                'lastmod'    => '2017-01-01T00:00:00+00:00',
                'changefreq' => 'always',
                'priority'   => 1.0,
                'title'      => 'Example - Homepage',
            ],[
                'loc'        => 'http://example.com/news',
                'lastmod'    => $formattedDate,
                'changefreq' => 'daily',
                'priority'   => 0.8,
                'title'      => null,
            ],[
                'loc'        => 'http://example.com/about-us',
                'lastmod'    => $formattedDate,
                'changefreq' => 'daily',
                'priority'   => 0.8,
                'title'      => null,
            ],[
                'loc'        => 'http://example.com/contact',
                'lastmod'    => $formattedDate,
                'changefreq' => 'daily',
                'priority'   => 0.8,
                'title'      => null,
            ],
        ];

        static::assertSame($expected, $this->sitemap->toArray());
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create an URL instance for tests.
     *
     * @return \Arcanedev\LaravelSitemap\Entities\Url
     */
    private static function createUrlSample(): Url
    {
        return Url::make('http://example.com')
            ->setChangeFreq(ChangeFrequency::ALWAYS)
            ->setPriority(1.0)
            ->setLastMod('2017-01-01 00:00:00')
            ->setTitle('Example - Homepage');
    }
}
