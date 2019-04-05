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

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = new Url('http://example.com');
    }

    protected function tearDown(): void
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
            static::assertInstanceOf($expected, $this->url);
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

        static::assertSame($expected, $this->url->toArray());
    }

    /** @test */
    public function it_can_make()
    {
        static::assertInstanceOf(
            \Arcanedev\LaravelSitemap\Entities\Url::class,
            $url = Url::make($loc = 'http://example.com')
        );

        static::assertSame($loc, $url->getLoc());
    }

    /** @test */
    public function it_can_make_from_array()
    {
        $url = Url::makeFromArray(['loc' => 'http://example.com']); // With minimal attributes

        static::assertInstanceOf(\Arcanedev\LaravelSitemap\Entities\Url::class, $url);

        $expected = [
            'loc'        => 'http://example.com',
            'lastmod'    => $url->formatLastMod(),
            'changefreq' => 'daily',
            'priority'   => 0.8,
            'title'      => null,
        ];

        static::assertSame($expected, $url->toArray());

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

        static::assertSame($expected, $url->toArray());
    }

    /** @test */
    public function it_can_get_and_set_location()
    {
        static::assertSame('http://example.com', $this->url->getLoc());

        $this->url->setLoc($loc = 'http://example.com/contact');

        static::assertSame($loc, $this->url->getLoc());
    }

    /** @test */
    public function it_can_get_and_set_last_modification_date()
    {
        static::assertInstanceOf(\DateTime::class, $this->url->getLastMod());
        static::assertSame(date('Y-m-d H:i'), $this->url->getLastMod()->format('Y-m-d H:i'));

        $this->url->setLastMod($date = '2017-01-01 00:00:00'); // String date

        static::assertSame($date, $this->url->getLastMod()->format('Y-m-d H:i:s'));

        $this->url->setLastMod($date = new \DateTime);

        static::assertSame($date, $this->url->getLastMod());
    }

    /** @test */
    public function it_can_get_and_set_change_freq()
    {
        static::assertSame(ChangeFrequency::DAILY, $this->url->getChangeFreq());

        $this->url->setChangeFreq($changeFreq = ChangeFrequency::WEEKLY);

        static::assertSame($changeFreq, $this->url->getChangeFreq());
    }

    /** @test */
    public function it_can_get_and_set_priority()
    {
        static::assertSame(0.8, $this->url->getPriority());

        $this->url->setPriority($priority = 1.0);

        static::assertSame($priority, $this->url->getPriority());
    }

    /** @test */
    public function it_can_get_and_set_title()
    {
        static::assertNull($this->url->getTitle());

        $this->url->setTitle($title = 'Example - Homepage');

        static::assertSame($title, $this->url->getTitle());
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

        static::assertSame($expected, $array = $this->url->toArray());
    }

    /** @test */
    public function it_can_convert_to_json()
    {
        $this->url->setLastMod('2017-01-01 00:00:00');

        $expected = json_encode($this->url->toArray());

        static::assertSame($expected, json_encode($this->url));
        static::assertSame($expected, $this->url->toJson());
    }

    /** @test */
    public function it_must_escape_the_url_location()
    {
        $url = Url::make('http://www.example.com/ümlat.php&q=name')
            ->setTitle('<hello type="shout">world</hello>');

        static::assertSame('http://www.example.com/ümlat.php&amp;q=name', $url->getLoc());
        static::assertSame('&lt;hello type="shout"&gt;world&lt;/hello&gt;', $url->getTitle());
    }

    /** @test */
    public function it_must_fail_if_loc_is_invalid_1()
    {
        $this->expectException(\Arcanedev\LaravelSitemap\Exceptions\SitemapException::class);
        $this->expectExceptionMessage('The [loc] attribute is required and must be string value.');

        Url::make(null);
    }

    /** @test */
    public function it_must_fail_if_loc_is_invalid_2()
    {
        $this->expectException(\Arcanedev\LaravelSitemap\Exceptions\SitemapException::class);
        $this->expectExceptionMessage('The [loc] attribute is required and must be string value.');

        Url::make(true);
    }

    /** @test */
    public function it_must_fail_if_priority_is_invalid_1()
    {
        $this->expectException(\Arcanedev\LaravelSitemap\Exceptions\SitemapException::class);
        $this->expectExceptionMessage('The [priority] value must be numeric.');

        Url::make($this->baseUrl)->setPriority('foo');
    }

    /** @test */
    public function it_must_fail_if_priority_is_invalid_2()
    {
        $this->expectException(\Arcanedev\LaravelSitemap\Exceptions\SitemapException::class);
        $this->expectExceptionMessage('The [priority] value must be between `0.0` and `1.0`, `2` was given.');

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

        static::assertSame($expected, $url->toArray());

        static::assertTrue($url->has('foo'));
        static::assertSame('bar', $url->get('foo'));

        $url->set('foo', 'baz');
        $expected['foo'] = 'baz';

        static::assertSame($expected, $url->toArray());

        unset($url['foo'], $expected['foo']);

        static::assertFalse($url->has('foo'));
        static::assertNull($url->get('foo'));

        static::assertSame($expected, $url->toArray());
    }
}
