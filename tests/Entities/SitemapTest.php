<?php namespace Arcanedev\LaravelSitemap\Tests\Entities;

use Arcanedev\LaravelSitemap\Contracts\Entities\ChangeFrequency;
use Arcanedev\LaravelSitemap\Entities\Sitemap;
use Arcanedev\LaravelSitemap\Entities\Url;
use Arcanedev\LaravelSitemap\Tests\TestCase;
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

    protected function setUp()
    {
        parent::setUp();

        $this->sitemap = new Sitemap;
    }

    protected function tearDown()
    {
        unset($this->sitemap);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Countable::class,
            \JsonSerializable::class,
            \Illuminate\Contracts\Support\Arrayable::class,
            \Illuminate\Contracts\Support\Jsonable::class,
            \Arcanedev\LaravelSitemap\Contracts\Entities\Sitemap::class,
            \Arcanedev\LaravelSitemap\Entities\Sitemap::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->sitemap);
        }

        $this->assertSame(0, $this->sitemap->count());
    }

    /** @test */
    public function it_can_add_url_to_the_collection()
    {
        $this->assertSame(0, $this->sitemap->count());

        $this->sitemap->add($this->createUrlSample());

        $this->assertSame(1, $this->sitemap->count());
    }

    /** @test */
    public function it_can_create_and_add_url_to_collection()
    {
        $this->assertSame(0, $this->sitemap->count());

        $this->sitemap->create('http://example.com', function (Url $url) {
            $url->setChangeFreq(ChangeFrequency::ALWAYS)
                ->setPriority(1.0)
                ->setLastMod('2017-01-01 00:00:00')
                ->setTitle('Example - Homepage');
        });

        $this->assertSame(1, $this->sitemap->count());

        /** @var \Arcanedev\LaravelSitemap\Entities\Url $url */
        $url = $this->sitemap->getUrls()->last();

        $this->assertSame('http://example.com', $url->getLoc());
        $this->assertSame(ChangeFrequency::ALWAYS, $url->getChangeFreq());
        $this->assertSame(1.0, $url->getPriority());
        $this->assertSame('2017-01-01 00:00:00', $url->getLastMod()->format('Y-m-d H:i:s'));
        $this->assertSame('Example - Homepage', $url->getTitle());
    }

    /** @test */
    public function it_can_check_if_has_an_existing_url()
    {
        $url = $this->createUrlSample();

        $this->assertFalse($this->sitemap->has($url->getLoc()));

        $this->sitemap->add($url);

        $this->assertTrue($this->sitemap->has($url->getLoc()));
    }

    /** @test */
    public function it_can_get_url_by_its_loc()
    {
        $url = $this->createUrlSample();

        $this->assertNull($this->sitemap->getUrl($url->getLoc()));

        $this->sitemap->add($url);

        $this->assertSame($url, $this->sitemap->getUrl($url->getLoc()));
    }

    /** @test */
    public function it_can_convert_to_array()
    {
        $this->assertSame([], $this->sitemap->toArray());

        $this->sitemap->add($this->createUrlSample());

        $expected = [
            [
                'loc'        => 'http://example.com',
                'lastmod'    => '2017-01-01T00:00:00+00:00',
                'changefreq' => 'always',
                'priority'   => 1.0,
                'title'      => 'Example - Homepage',
            ]
        ];

        $this->assertSame($expected, $this->sitemap->toArray());
    }

    /** @test */
    public function it_can_convert_to_json()
    {
        $expected = '[]';

        $this->assertSame($expected, json_encode($this->sitemap));
        $this->assertSame($expected, $this->sitemap->toJson());

        $this->sitemap->add($this->createUrlSample());

        $expected = json_encode($this->sitemap->toArray());

        $this->assertSame($expected, json_encode($this->sitemap));
        $this->assertSame($expected, $this->sitemap->toJson());
    }

    /** @test */
    public function it_should_treats_urls_as_the_basic_collection_class()
    {
        $this->sitemap = $this->createBlogSitemap();

        $this->assertMatchesSnapshot(
            $this->sitemap->getUrls()->pluck('title', 'loc')->toArray()
        );
    }

    /** @test */
    public function it_can_check_if_number_of_urls_is_exceeded()
    {
        // Max is 500 for tests

        $sitemap = $this->createBlogSitemap();

        $this->assertSame(11, $sitemap->count());
        $this->assertFalse($this->createBlogSitemap()->isExceeded());

        $sitemap = $this->createBlogSitemap(498);

        $this->assertSame(499, $sitemap->count()); // 1 blog index + 498 posts = 499 urls
        $this->assertTrue($sitemap->isExceeded());

        $sitemap = $this->createBlogSitemap(499);

        $this->assertSame(500, $sitemap->count()); // 1 blog index + 499 posts = 500 urls
        $this->assertTrue($sitemap->isExceeded());
    }

    /** @test */
    public function it_can_set_urls()
    {
        $this->assertSame(0, $this->sitemap->count());

        $urls = collect([
            ['loc' => 'http://example.com'],
            ['loc' => 'http://example.com/about-us'],
            ['loc' => 'http://example.com/contact'],
        ]);

        $now = new \DateTime;

        $this->sitemap->setUrls($urls->transform(function (array $item) use ($now) {
            return Url::makeFromArray($item)->setLastMod($now);
        }));

        $this->assertSame(3, $this->sitemap->count());

        $formattedDate = $now->format(\DateTime::ATOM);

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

        $this->assertSame($expected, $this->sitemap->toArray());
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
    private function createUrlSample()
    {
        return Url::make('http://example.com')
            ->setChangeFreq(ChangeFrequency::ALWAYS)
            ->setPriority(1.0)
            ->setLastMod('2017-01-01 00:00:00')
            ->setTitle('Example - Homepage');
    }
}
