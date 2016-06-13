<?php namespace Arcanedev\LaravelSitemap\Tests\Entities;

use Arcanedev\LaravelSitemap\Entities\SitemapItem;
use Arcanedev\LaravelSitemap\Tests\TestCase;

/**
 * Class     SitemapItemTest
 *
 * @package  Arcanedev\LaravelSitemap\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapItemTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\LaravelSitemap\Entities\SitemapItem */
    private $item;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->item = new SitemapItem([]);
    }

    public function tearDown()
    {
        unset($this->item);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(
            \Arcanedev\LaravelSitemap\Entities\SitemapItem::class,
            $this->item
        );
    }

    /** @test */
    public function it_can_make()
    {
        $this->item = SitemapItem::make([]);

        $this->assertInstanceOf(
            \Arcanedev\LaravelSitemap\Entities\SitemapItem::class,
            $this->item
        );
    }

    /** @test */
    public function it_can_get_default()
    {
        $this->assertEquals('/', $this->item->getLoc());
        $this->assertNull($this->item->getLastmod());
        $this->assertNull($this->item->getFreq());
        $this->assertNull($this->item->getPriority());
        $this->assertNull($this->item->getTitle());
        $this->assertEmpty($this->item->getImages());
        $this->assertEmpty($this->item->getVideos());
        $this->assertEmpty($this->item->getTranslations());
        $this->assertEmpty($this->item->getAlternates());

        $googlenews = $this->item->getGooglenews();

        foreach (['sitename', 'language', 'publication_date'] as $key) {
            $this->assertArrayHasKey($key, $googlenews);
        }

        $this->assertEmpty($googlenews['sitename']);
        $this->assertEquals('en', $googlenews['language']);
        $this->assertRegExp('(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})', $googlenews['publication_date']);
    }

    /** @test */
    public function it_can_set_and_get_loc()
    {
        $this->item->setLoc($loc = 'http://www.arcanedev.net');

        $this->assertEquals($loc, $this->item->getLoc());
    }

    /** @test */
    public function it_can_set_and_get_lastmod()
    {
        $this->item->setLastmod($dt = new \DateTime);

        $this->assertEquals($dt->format('c'), $this->item->getLastmod());
    }

    /** @test */
    public function it_can_set_and_get_priority()
    {
        $this->item->setPriority($priority = '1.0');

        $this->assertEquals($priority, $this->item->getPriority());
    }

    /** @test */
    public function it_can_set_and_get_freq()
    {
        $this->item->setFreq($freq = 'daily');

        $this->assertEquals($freq, $this->item->getFreq());
    }

    /** @test */
    public function it_can_set_and_get_title()
    {
        $this->item->setTitle($title = 'ARCANEDEV');

        $this->assertEquals($title, $this->item->getTitle());
    }

    /** @test */
    public function it_can_set_and_get_images()
    {
        $this->item->setImages($images = [
            ['url' => 'assets/img/logo.png'],
            ['url' => 'assets/img/banner.jpg'],
        ]);

        $this->assertEquals($images, $this->item->getImages());
    }

    /** @test */
    public function it_can_set_and_get_videos()
    {
        $this->item->setVideos($videos = [
            [
                'title'       => 'ARCANEDEV - Introduction',
                'description' => 'This is an introduction about ARCANEDEV.',
                'content_loc' => 'https://www.arcanedev.net/assets/videos/intro.mp4',
            ],[
                'title'       => 'ARCANEDEV - Demo',
                'description' => 'This is a demo description.',
                'content_loc' => 'https://www.arcanedev.net/assets/videos/demo.mp4',
            ],
        ]);

        $this->assertEquals($videos, $this->item->getVideos());
    }

    /** @test */
    public function it_can_set_can_get_translations()
    {
        $this->item->setTranslations($translations = [
            ['language' => 'en', 'url' => '/en'],
            ['language' => 'fr', 'url' => '/fr'],
            ['language' => 'es', 'url' => '/es'],
        ]);

        $this->assertEquals($translations, $this->item->getTranslations());
    }

    /** @test */
    public function it_can_set_and_get_googlenews()
    {
        $expected = [
            'sitename'         => 'ARCANEDEV',
            'language'         => 'en',
            'publication_date' => '2016-01-01 00:00:00',
        ];

        $this->item->setGooglenews($expected);

        $googlenews = $this->item->getGooglenews();

        $this->assertArrayHasKey('sitename',         $googlenews);
        $this->assertArrayHasKey('language',         $googlenews);
        $this->assertArrayHasKey('publication_date', $googlenews);

        $this->assertEquals($expected, $googlenews);
    }

    /** @test */
    public function it_can_set_and_get_alternates()
    {
        $this->item->setAlternates($alternates = []);

        $this->assertEquals($alternates, $this->item->getAlternates());
    }

    /** @test */
    public function it_can_access_like_an_array()
    {
        $keys = [
            'loc', 'lastmod', 'freq', 'priority', 'title',
            'images', 'videos', 'translations', 'googlenews', 'alternates'
        ];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $this->item);
        }

        $this->assertEquals('/', $this->item['loc']);
        $this->assertNull($this->item['lastmod']);
    }

    /** @test */
    public function it_can_set_and_get_like_an_array()
    {
        $this->assertEquals('/', $this->item['loc']);

        $this->item['loc'] = $loc = 'http://www.example.com/';

        $this->assertEquals($loc, $this->item['loc']);

        $this->item['lastmod'] = $dt = '2016-01-01 00:00:00';

        $this->assertNotEquals($dt, $this->item['lastmod']);
        $this->assertEquals((new \DateTime($dt))->format('c'), $this->item['lastmod']);
    }

    /** @test */
    public function it_can_unset_like_an_array()
    {
        $this->item['lastmod'] = '2016-01-01 00:00:00';

        $this->assertNotNull($this->item['lastmod']);

        unset($this->item['lastmod']);

        $this->assertNull($this->item['lastmod']);
    }
}
