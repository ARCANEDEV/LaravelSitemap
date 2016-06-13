<?php namespace Arcanedev\LaravelSitemap\Tests;

/**
 * Class     SitemapManagerTest
 *
 * @package  Arcanedev\LaravelSitemap\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapManagerTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\LaravelSitemap\SitemapManager  */
    protected $sitemap;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->sitemap = $this->app->make('sitemap.manager');
    }

    public function tearDown()
    {
        unset($this->sitemap);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Arcanedev\LaravelSitemap\SitemapManager::class,
            \Arcanedev\LaravelSitemap\Contracts\SitemapManager::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->sitemap);
        }

        $this->sitemap = $this->app->make(\Arcanedev\LaravelSitemap\Contracts\SitemapManager::class);

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->sitemap);
        }

        $this->sitemap = sitemap();

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->sitemap);
        }
    }

    /** @test */
    public function it_can_set_and_get_attributes()
    {
        $this->sitemap->setLink('ARCANEDEV Link');
        $this->sitemap->setTitle('ARCANEDEV Title');
        $this->sitemap->setCacheEnabled(true);
        $this->sitemap->setCacheKey('lv-sitemap');
        $this->sitemap->setCacheDuration(72000);
        $this->sitemap->setEscaping(false);
        $this->sitemap->setUseLimitSize(true);
        $this->sitemap->setMaxSize(10000);
        $this->sitemap->setUseStyles(false);
        $this->sitemap->setStyleLocation('https://static.foobar.tld/xsl-styles/');

        $this->assertEquals('ARCANEDEV Link', $this->sitemap->getLink());
        $this->assertEquals('ARCANEDEV Title', $this->sitemap->getTitle());
        $this->assertTrue($this->sitemap->isCacheEnabled());
        $this->assertEquals('lv-sitemap', $this->sitemap->getCacheKey());
        $this->assertEquals(72000, $this->sitemap->getCacheDuration());
        $this->assertFalse($this->sitemap->isEscaped());
        $this->assertTrue($this->sitemap->getUseLimitSize());
        $this->assertEquals(10000, $this->sitemap->getMaxSize());
        $this->assertFalse($this->sitemap->getUseStyles());
        $this->assertEquals('https://static.foobar.tld/xsl-styles/', $this->sitemap->getStyleLocation());
    }

    /** @test */
    public function it_can_add()
    {
        // dummy data
        $translations = [
            ['language' => 'de', 'url' => '/pageDe'],
            ['language' => 'bg', 'url' => '/pageBg?id=1&sid=2'],
        ];

        $translationsEscaped = [
            ['language' => 'de', 'url' => '/pageDe'],
            ['language' => 'bg', 'url' => '/pageBg?id=1&amp;sid=2'],
        ];

        $images = [
            ["url" => "test.png"],
            ["url" => "<&>"],
        ];

        $imagesEscaped = [
            ["url" => "test.png"],
            ["url" => "&lt;&amp;&gt;"],
        ];

        $videos = [
            [
                'title'       => 'TestTitle',
                'description' => 'TestDescription',
                'content_loc' => 'https://www.arcanedev.net/test-video.flv',
                'uploader'    => [
                    'uploader' => 'ARCANEDEV',
                    'info'     => 'https://www.arcanedev.net'
                ],
                'gallery_loc' => [
                    'title'       => 'testGalleryTitle',
                    'gallery_loc' => 'https://www.arcanedev.net/test-gallery'
                ],
                'price'       => [
                    'currency' => 'EUR',
                    'price'    => '100.00'
                ],
                'restriction' => [
                    'relationship' => 'allow',
                    'restriction'  => 'IE GB US CA'
                ],
                'player_loc'  => [
                    'player_loc'  => 'https://www.arcanedev.net/test-player.flv',
                    'allow_embed' => 'yes',
                    'autoplay'    => 'ap=1',
                ],
                'thumbnail_loc'         => 'https://www.arcanedev.net/test-video.png',
                'duration'              => '600',
                'expiration_date'       => '2015-12-30T23:59:00+02:00',
                'rating'                => '5.00',
                'view_count'            => '100',
                'publication_date'      => '2015-05-30T23:59:00+02:00',
                'family_friendly'       => 'yes',
                'requires_subscription' => 'no',
            ],[
                'title'       => 'TestTitle2&',
                'description' => 'TestDescription2&',
                'content_loc' => 'https://www.arcanedev.net/test-video.flv',
            ]
        ];

        $googleNews = [
            'sitename'         => 'Foo',
            'language'         => 'en',
            'publication_date' => '2016-01-03',
            'access'           => 'Subscription',
            'keywords'         => 'googlenews, sitemap',
            'genres'           => 'PressRelease, Blog',
            'stock_tickers'    => 'NASDAQ:A, NASDAQ:B',
        ];

        $alternates = [
            [
                'media' => 'only screen and (max-width: 640px)',
                'url'   => 'https://m.foobar.tld',
            ],[
                'media' => 'only screen and (max-width: 960px)',
                'url'   => 'https://foobar.tld',
            ],
        ];

        // add new sitemap items
        $this->sitemap->add('TestLoc',  '2016-02-29 00:00:00', 0.95, 'weekly', $images, 'TestTitle', $translations, $videos, $googleNews, $alternates);
        $this->sitemap->add('TestLoc2', '2016-03-01 00:00:00', 0.85, 'daily');

        $items     = $this->sitemap->getItems();
        /** @var \Arcanedev\LaravelSitemap\Entities\SitemapItem $firstItem */
        $firstItem = $items->first();

        // count items
        $this->assertCount(2, $items);

        // item attributes
        $this->assertEquals('TestLoc', $firstItem->getLoc());
        $this->assertEquals('2016-02-29T00:00:00+00:00', $firstItem->getLastmod());
        $this->assertEquals('0.95', $firstItem->getPriority());
        $this->assertEquals('weekly', $firstItem->getFreq());
        $this->assertEquals('TestTitle', $firstItem->getTitle());

        // images
        $this->assertEquals($imagesEscaped, $firstItem->getImages());

        // translations
        $this->assertEquals($translationsEscaped, $firstItem->getTranslations());

        // videos
        $videos = $firstItem->getVideos();
        $this->assertEquals($videos[0]['content_loc'], $firstItem->getVideos()[0]['content_loc']);
        $this->assertEquals($videos[1]['content_loc'], $firstItem->getVideos()[1]['content_loc']);
        $this->assertEquals('TestTitle2&amp;', $firstItem->getVideos()[1]['title']);
        $this->assertEquals('TestDescription2&amp;', $firstItem->getVideos()[1]['description']);

        // googlenews
        $this->assertArrayHasKey('sitename', $firstItem->getGooglenews());
        $this->assertArrayHasKey('publication_date', $firstItem->getGooglenews());
        $this->assertEquals($googleNews['sitename'], $firstItem->getGooglenews()['sitename']);
        $this->assertEquals($googleNews['publication_date'], $firstItem->getGooglenews()['publication_date']);

        // alternates
        $this->assertEquals($alternates[1]['url'], $firstItem->getAlternates()[1]['url']);

        $this->assertEquals('TestLoc2', $items->get(1)->getLoc());
    }

    /** @test */
    public function it_can_add_items()
    {
        // add one item
        $this->sitemap->addItem([
            'loc'      => 'TestLoc',
            'lastmod'  => '2016-01-01 00:00:00',
            'priority' => 0.95,
            'freq'     => 'daily',
        ]);

        $this->sitemap->addItems([
            [
                'loc'      => 'TestLoc2',
                'lastmod'  => '2016-01-02 00:00:00',
                'priority' => 0.85,
                'freq'     => 'daily',
            ],[
                'loc'      => 'TestLoc3',
                'lastmod'  => '2016-01-03 00:00:00',
                'priority' => 0.75,
                'freq'     => 'daily',
            ],
        ]);

        $items = $this->sitemap->getItems();

        // count items
        $this->assertCount(3, $items);

        // item attributes
        $this->assertEquals('TestLoc',  $items->get(0)->getLoc());
        $this->assertEquals('TestLoc2', $items->get(1)->getLoc());
        $this->assertEquals('TestLoc3', $items->get(2)->getLoc());
    }
}
