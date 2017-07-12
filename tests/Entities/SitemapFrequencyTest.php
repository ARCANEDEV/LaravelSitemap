<?php namespace Arcanedev\LaravelSitemap\Tests\Entities;

use Arcanedev\LaravelSitemap\Entities\SitemapFrequency;
use Arcanedev\LaravelSitemap\Tests\TestCase;

/**
 * Class     SitemapFrequencyTest
 *
 * @package  Arcanedev\LaravelSitemap\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapFrequencyTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_get_keys()
    {
        $this->assertCount(7, SitemapFrequency::keys());
    }

    /** @test */
    public function it_can_get_all()
    {
        $all = SitemapFrequency::all();

        $this->assertCount(7, $all);

        foreach ($all as $key => $translated) {
            $this->assertNotEquals($key, $translated);
            $this->assertEquals(trans("sitemap::frequencies.$key"), $translated);
        }
    }

    /** @test */
    public function it_can_check_if_exists()
    {
        $expectations = [
            'always'  => true,
            'hourly'  => true,
            'daily'   => true,
            'weekly'  => true,
            'monthly' => true,
            'yearly'  => true,
            'never'   => true,
            'century' => false,
        ];

        foreach ($expectations as $key => $expected) {
            $this->assertSame($expected, SitemapFrequency::exists($key));
        }
    }

    /** @test */
    public function it_can_get_translated_one()
    {
        $this->assertSame('Always', SitemapFrequency::get('always'));

        $this->assertSame('sitemap::frequencies.century', SitemapFrequency::get('century'));

        $this->assertSame('Toujours', SitemapFrequency::get('always', 'fr'));
    }
}
