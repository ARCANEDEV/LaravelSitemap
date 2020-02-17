<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSitemap\Tests;

use Arcanedev\LaravelSitemap\LaravelSitemapServiceProvider;

/**
 * Class     LaravelSitemapServiceProviderTest
 *
 * @package  Arcanedev\LaravelSitemap\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LaravelSitemapServiceProviderTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelSitemap\LaravelSitemapServiceProvider */
    private $provider;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(LaravelSitemapServiceProvider::class);
    }

    protected function tearDown(): void
    {
        unset($this->provider);

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
            \Illuminate\Support\ServiceProvider::class,
            \Illuminate\Contracts\Support\DeferrableProvider::class,
            \Arcanedev\Support\Providers\ServiceProvider::class,
            \Arcanedev\Support\Providers\PackageServiceProvider::class,
            LaravelSitemapServiceProvider::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->provider);
        }
    }

    /** @test */
    public function it_can_provides(): void
    {
        $expected = [
            \Arcanedev\LaravelSitemap\Contracts\SitemapManager::class,
        ];

        static::assertSame($expected, $this->provider->provides());
    }
}
