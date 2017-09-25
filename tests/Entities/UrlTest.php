<?php namespace Arcanedev\LaravelSitemap\Tests\Entities;

use Arcanedev\LaravelSitemap\Contracts\Entities\ChangeFrequency;
use Arcanedev\LaravelSitemap\Entities\Url;
use Arcanedev\LaravelSitemap\Tests\TestCase;

/**
 * Class     UrlTest
 *
 * @package  Arcanedev\LaravelSitemap\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UrlTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelSitemap\Entities\Url */
    protected $url;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    protected function setUp()
    {
        parent::setUp();

        $this->url = new Url('http://example.com');
    }

    protected function tearDown()
    {
        unset($this->url);

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
            \JsonSerializable::class,
            \Illuminate\Contracts\Support\Arrayable::class,
            \Illuminate\Contracts\Support\Jsonable::class,
            \Arcanedev\LaravelSitemap\Entities\Url::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->url);
        }
    }

    /** @test */
    public function it_can_also_instantiate_with_array()
    {
        $this->url = new Url([
            'title'      => 'Home page',
            'loc'        => 'http://example.com',
            'lastmod'    => $now = new \DateTime,
            'changefreq' => 'hourly',
            'priority'   => 1.0,
        ]);

        $expected = [
            'title'      => 'Home page',
            'loc'        => 'http://example.com',
            'lastmod'    => $now->format(\DateTime::ATOM),
            'changefreq' => 'hourly',
            'priority'   => 1.0,
        ];

        $this->assertSame($expected, $this->url->toArray());
    }

    /** @test */
    public function it_can_make()
    {
        $this->assertInstanceOf(
            \Arcanedev\LaravelSitemap\Entities\Url::class,
            $url = Url::make($loc = 'http://example.com')
        );

        $this->assertSame($loc, $url->getLoc());
    }

    /** @test */
    public function it_can_make_from_array()
    {
        $url = Url::makeFromArray(['loc' => 'http://example.com']); // With minimal attributes

        $this->assertInstanceOf(\Arcanedev\LaravelSitemap\Entities\Url::class, $url);

        $expected = [
            'loc'        => 'http://example.com',
            'lastmod'    => $url->formatLastMod(),
            'changefreq' => 'daily',
            'priority'   => 0.8,
            'title'      => null,
        ];

        $this->assertSame($expected, $url->toArray());

        $now = new \DateTime();
        $url = Url::makeFromArray([
            'title'      => 'Contact Page',
            'loc'        => 'http://example.com/contact',
            'lastmod'    => $now,
            'changefreq' => 'MONTHLY',
            'priority'   => '0.49',
        ]);

        $expected = [
            'title'      => 'Contact Page',
            'loc'        => 'http://example.com/contact',
            'lastmod'    => $now->format(\DateTime::ATOM),
            'changefreq' => 'monthly',
            'priority'   => 0.5,
        ];

        $this->assertSame($expected, $url->toArray());
    }

    /** @test */
    public function it_can_get_and_set_location()
    {
        $this->assertSame('http://example.com', $this->url->getLoc());

        $this->url->setLoc($loc = 'http://example.com/contact');

        $this->assertSame($loc, $this->url->getLoc());
    }

    /** @test */
    public function it_can_get_and_set_last_modification_date()
    {
        $this->assertInstanceOf(\DateTime::class, $this->url->getLastMod());
        $this->assertSame(date('Y-m-d H:i'), $this->url->getLastMod()->format('Y-m-d H:i'));

        $this->url->setLastMod($date = '2017-01-01 00:00:00'); // String date

        $this->assertSame($date, $this->url->getLastMod()->format('Y-m-d H:i:s'));

        $this->url->setLastMod($date = new \DateTime);

        $this->assertSame($date, $this->url->getLastMod());
    }

    /** @test */
    public function it_can_get_and_set_change_freq()
    {
        $this->assertSame(ChangeFrequency::DAILY, $this->url->getChangeFreq());

        $this->url->setChangeFreq($changeFreq = ChangeFrequency::WEEKLY);

        $this->assertSame($changeFreq, $this->url->getChangeFreq());
    }

    /** @test */
    public function it_can_get_and_set_priority()
    {
        $this->assertSame(0.8, $this->url->getPriority());

        $this->url->setPriority($priority = 1.0);

        $this->assertSame($priority, $this->url->getPriority());
    }

    /** @test */
    public function it_can_get_and_set_title()
    {
        $this->assertNull($this->url->getTitle());

        $this->url->setTitle($title = 'Example - Homepage');

        $this->assertSame($title, $this->url->getTitle());
    }

    /** @test */
    public function it_can_convert_to_array()
    {
        $this->url->setLastMod('2017-01-01 00:00:00');

        $expected = [
            'loc'        => 'http://example.com',
            'lastmod'    => '2017-01-01T00:00:00+00:00',
            'changefreq' => 'daily',
            'priority'   => 0.8,
            'title'      => null,
        ];

        $this->assertSame($expected, $array = $this->url->toArray());
    }

    /** @test */
    public function it_can_convert_to_json()
    {
        $this->url->setLastMod('2017-01-01 00:00:00');

        $expected = json_encode($this->url->toArray());

        $this->assertSame($expected, json_encode($this->url));
        $this->assertSame($expected, $this->url->toJson());
    }

    /** @test */
    public function it_must_escape_the_url_location()
    {
        $url = Url::make('http://www.example.com/ümlat.php&q=name')
            ->setTitle('<hello type="shout">world</hello>');

        $this->assertSame('http://www.example.com/ümlat.php&amp;q=name', $url->getLoc());
        $this->assertSame('&lt;hello type="shout"&gt;world&lt;/hello&gt;', $url->getTitle());
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\LaravelSitemap\Exceptions\SitemapException
     * @expectedExceptionMessage  The [loc] attribute is required and must be string value.
     */
    public function it_must_fail_if_loc_is_invalid_1()
    {
        Url::make(null);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\LaravelSitemap\Exceptions\SitemapException
     * @expectedExceptionMessage  The [loc] attribute is required and must be string value.
     */
    public function it_must_fail_if_loc_is_invalid_2()
    {
        Url::make(true);
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\LaravelSitemap\Exceptions\SitemapException
     * @expectedExceptionMessage  The [priority] value must be numeric.
     */
    public function it_must_fail_if_priority_is_invalid_1()
    {
        Url::make($this->baseUrl)->setPriority('foo');
    }

    /**
     * @test
     *
     * @expectedException         \Arcanedev\LaravelSitemap\Exceptions\SitemapException
     * @expectedExceptionMessage  The [priority] value must be between `0.0` and `1.0`, `2` was given.
     */
    public function it_must_fail_if_priority_is_invalid_2()
    {
        Url::make($this->baseUrl)->setPriority('2.0');
    }

    /** @test */
    public function it_can_manipulate_extra_attributes()
    {
        $url = Url::makeFromArray([
            'loc'        => 'http://example.com',
            'lastmod'    => \Carbon\Carbon::create(2017, 01, 01, 00, 00, 00),
            'changefreq' => ChangeFrequency::DAILY,
            'priority'   => 1.0,
            'title'      => 'Hello world',
            'foo'        => 'bar',
        ]);

        $expected = [
            'loc'        => 'http://example.com',
            'lastmod'    => '2017-01-01T00:00:00+00:00',
            'changefreq' => 'daily',
            'priority'   => 1.0,
            'title'      => 'Hello world',
            'foo'        => 'bar',
        ];

        $this->assertSame($expected, $url->toArray());

        $this->assertTrue($url->has('foo'));
        $this->assertSame('bar', $url->get('foo'));

        $url->set('foo', 'baz');
        $expected['foo'] = 'baz';

        $this->assertSame($expected, $url->toArray());

        unset($url['foo'], $expected['foo']);

        $this->assertFalse($url->has('foo'));
        $this->assertNull($url->get('foo'));

        $this->assertSame($expected, $url->toArray());
    }
}
