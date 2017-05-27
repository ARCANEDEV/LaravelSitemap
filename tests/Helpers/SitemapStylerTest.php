<?php namespace Arcanedev\LaravelSitemap\Tests\Helpers;

use Arcanedev\LaravelSitemap\Helpers\SitemapStyler;
use Arcanedev\LaravelSitemap\Tests\TestCase;

/**
 * Class     SitemapStylerTest
 *
 * @package  Arcanedev\LaravelSitemap\Tests\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapStylerTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\LaravelSitemap\Helpers\SitemapStyler */
    protected $styler;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->publishStyles();
        $this->styler = $this->app->make(\Arcanedev\LaravelSitemap\Contracts\SitemapStyler::class);
    }

    public function tearDown()
    {
        unset($this->styler);

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
            \Arcanedev\LaravelSitemap\Helpers\SitemapStyler::class,
            \Arcanedev\LaravelSitemap\Contracts\SitemapStyler::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->styler);
        }

        $this->assertInstanceOf(
            \Arcanedev\LaravelSitemap\Helpers\SitemapStyler::class,
            $this->app->make(\Arcanedev\LaravelSitemap\Contracts\SitemapStyler::class)
        );
    }

    /** @test */
    public function it_can_enable_and_disable()
    {
        $this->assertTrue($this->styler->isEnabled());

        $this->styler->disable();

        $this->assertFalse($this->styler->isEnabled());

        $this->styler->enable();

        $this->assertTrue($this->styler->isEnabled());
    }

    /** @test */
    public function it_can_set_and_get_location()
    {
        $this->assertNull($this->styler->getLocation());

        $path = public_path('sitemap-styles');
        $this->styler->setLocation($path);

        $this->assertEquals($path . '/', $this->styler->getLocation());

        $this->styler->setLocation(null);

        $this->assertNull($this->styler->getLocation());
    }

    /** @test */
    public function it_can_get_sitemap_format()
    {
        $formats = [
            SitemapStyler::GOOGLE_NEWS_FORMAT,
            SitemapStyler::MOBILE_FORMAT,
            SitemapStyler::SITEMAPINDEX_FORMAT,
            SitemapStyler::XML_FORMAT,
        ];

        foreach ($formats as $format) {
            $this->assertEquals(
                "{$this->baseUrl}/vendor/sitemap/styles/$format.xsl",
                $this->styler->get($format)
            );
        }

        $this->assertNull($this->styler->get('feed'));
    }

    /** @test */
    public function it_can_get_sitemap_style()
    {
        $formats = [
            SitemapStyler::GOOGLE_NEWS_FORMAT,
            SitemapStyler::MOBILE_FORMAT,
            SitemapStyler::SITEMAPINDEX_FORMAT,
            SitemapStyler::XML_FORMAT,
        ];

        foreach ($formats as $format) {
            $style = "vendor/sitemap/styles/$format.xsl";

            $this->assertEquals($style, $this->styler->get($format, $style));
        }
    }

    /** @test */
    public function it_can_get_sitemap_format_from_custom_location()
    {
        $this->styler->setLocation('vendor/sitemap/styles');

        $formats = [
            SitemapStyler::GOOGLE_NEWS_FORMAT,
            SitemapStyler::MOBILE_FORMAT,
            SitemapStyler::SITEMAPINDEX_FORMAT,
            SitemapStyler::XML_FORMAT,
        ];

        foreach ($formats as $format) {
            $this->assertEquals(
                "vendor/sitemap/styles/$format.xsl",
                $this->styler->get($format)
            );
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function publishStyles()
    {
        $this->artisan('vendor:publish', [
            '--provider' => \Arcanedev\LaravelSitemap\LaravelSitemapServiceProvider::class
        ]);
    }
}
