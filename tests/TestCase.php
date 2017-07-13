<?php namespace Arcanedev\LaravelSitemap\Tests;

use Arcanedev\LaravelSitemap\Contracts\Entities\ChangeFrequency;
use Arcanedev\LaravelSitemap\Entities\Sitemap;
use Arcanedev\LaravelSitemap\Entities\Url;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\LaravelSitemap\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Arcanedev\LaravelSitemap\LaravelSitemapServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            //
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('sitemap.urls-max-size', 100);
    }

    /* -----------------------------------------------------------------
     |  Custom assertions
     | -----------------------------------------------------------------
     */

    /**
     * Asserts that an array has these specified keys.
     *
     * @param  array   $keys
     * @param  array   $array
     * @param  string  $message
     */
    public static function assertArrayHasKeys(array $keys, $array, $message = '')
    {
        foreach ($keys as $key) {
            static::assertArrayHasKey($key, $array, $message);
        }
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    protected function createPagesSitemap()
    {
        $baseUrl = 'http://example.com';
        $lastMod = '2017-01-01 00:00:00';

        return (new Sitemap)
            ->setPath("{$baseUrl}/sitemap-pages.xml")
            ->add(Url::make("{$baseUrl}/")->setLastMod($lastMod))
            ->add(Url::make("{$baseUrl}/about-us")->setLastMod($lastMod))
            ->add(Url::make("{$baseUrl}/contact")->setLastMod($lastMod));
    }

    protected function createBlogSitemap($times = 10)
    {
        $baseUrl = 'http://example.com';
        $lastMod = '2017-01-02 00:00:00';
        $sitemap = (new Sitemap)
            ->setPath("{$baseUrl}/sitemap-blog.xml")
            ->create("{$baseUrl}/blog", function (Url $url) use ($lastMod) {
                $url->setTitle('Blog page')
                    ->setChangeFreq(ChangeFrequency::WEEKLY)
                    ->setPriority(.7)
                    ->setLastMod($lastMod);
            });

        foreach (range(1, $times) as $i) {
            $sitemap->add(
                Url::make("{$baseUrl}/blog/posts/post-{$i}")
                   ->setTitle("Blog / Post {$i}")
                   ->setChangeFreq(ChangeFrequency::MONTHLY)
                   ->setPriority(.5)
                   ->setLastMod($lastMod)
            );
        }

        return $sitemap;
    }
}
