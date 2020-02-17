<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSitemap\Tests;

use Arcanedev\LaravelSitemap\Contracts\SitemapManager;
use Illuminate\Http\Response;
use Arcanedev\LaravelSitemap\Entities\{Sitemap, Url};
use Spatie\Snapshots\MatchesSnapshots;

/**
 * Class     SitemapManagerTest
 *
 * @package  Arcanedev\LaravelSitemap\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapManagerTest extends TestCase
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

    /** @var \Arcanedev\LaravelSitemap\Contracts\SitemapManager */
    private $manager;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = $this->app->make(SitemapManager::class);
    }

    protected function tearDown(): void
    {
        unset($this->manager);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $expectations = [
            SitemapManager::class,
            \Arcanedev\LaravelSitemap\SitemapManager::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->manager);
        }
    }

    /** @test */
    public function it_should_return_empty_sitemaps_collection_on_creation(): void
    {
        static::assertCount(0, $this->manager->all());
        static::assertSame(0, $this->manager->count());
    }

    /** @test */
    public function it_can_add_a_sitemap_to_collection(): void
    {
        $this->manager->add('pages', $sitemap = new Sitemap);

        static::assertSame(1, $this->manager->count());
        static::assertCount(1, $sitemaps = $this->manager->all());

        static::assertSame($sitemap, $sitemaps->first());
    }

    /** @test */
    public function it_can_get_a_sitemap_by_its_name(): void
    {
        $this->manager->add('blog', $sitemap = new Sitemap);

        static::assertSame(1, $this->manager->count());
        static::assertCount(1, $sitemaps = $this->manager->all());

        $sitemap = $this->manager->get('blog');

        static::assertInstanceOf(Sitemap::class, $sitemap);
    }

    /** @test */
    public function it_can_create_and_add_a_sitemap_to_collection(): void
    {
        $this->manager->create('pages', function (Sitemap $sitemap) {
            $sitemap->add(Url::make('http://example.com'));
        });

        $sitemap = $this->manager->get('pages');

        static::assertSame(1, $sitemap->count());

        static::assertTrue($sitemap->has('http://example.com'));
        static::assertFalse($sitemap->has('http://example.com/blog'));
    }

    /** @test */
    public function it_can_forget_sitemaps_from_collection(): void
    {
        $this->populatedManager();

        static::assertCount(2, $this->manager);
        static::assertTrue($this->manager->has('pages'));
        static::assertTrue($this->manager->has('blog'));

        $this->manager->forget('blog');
    }

    /** @test */
    public function it_can_convert_to_array(): void
    {
        static::assertSame([], $this->manager->toArray());

        $this->populatedManager();

        $result = $this->manager->toArray();

        static::assertArrayHasKeys(['pages', 'blog'], $result);

        foreach ($result as $sitemapUrls) {
            foreach ($sitemapUrls as $url) {
                static::assertArrayHasKeys(['loc', 'lastmod', 'changefreq', 'priority'], $url);
            }
        }
    }

    /** @test */
    public function it_can_convert_to_json(): void
    {
        $expected = '[]';

        static::assertSame($expected, json_encode($this->manager));
        static::assertSame($expected, $this->manager->toJson());

        $this->populatedManager();

        $expected = json_encode($this->manager->toArray());

        static::assertSame($expected, json_encode($this->manager));
        static::assertSame($expected, $this->manager->toJson());
    }


    /** @test */
    public function it_can_render_sitemaps(): void
    {
        $this->populatedManager();

        static::assertMatchesXmlSnapshot($this->manager->render());
        static::assertMatchesXmlSnapshot($this->manager->render('pages'));
        static::assertMatchesXmlSnapshot($this->manager->render('blog'));

        static::assertNull($this->manager->render('admin')); // Not available
    }

    /** @test */
    public function it_should_return_null_when_rendering_an_empty_manager(): void
    {
        static::assertNull($this->manager->render());
    }

    /** @test */
    public function it_can_save_sitemap_index(): void
    {
        $directory = __DIR__.'/__temp__';
        $this->manager->save($path = "$directory/sitemap.xml");

        static::assertFileNotExists($path);

        $this->populatedManager();

        $this->manager->save($path);

        static::assertFileExists($path);
        static::assertXmlStringEqualsXmlFile($path, $this->manager->render());

        unlink($path);
    }

    /** @test */
    public function it_can_save_sitemaps(): void
    {
        $directory = __DIR__.'/__temp__';

        foreach (['pages', 'blog'] as $name) {
            static::assertFileNotExists($path = "{$directory}/sitemap-{$name}.xml");

            $this->populatedManager();

            $this->manager->save($path, $name);

            static::assertFileExists($path);
            static::assertXmlStringEqualsXmlFile($path, $this->manager->render($name));

            unlink($path); // Delete the saved file
        }
    }

    /** @test */
    public function it_can_save_the_single_sitemap_instead_of_index(): void
    {
        $directory = __DIR__.'/__temp__';
        $path = "$directory/sitemap.xml";

        $this->manager->add('pages', $this->createPagesSitemap());

        $this->manager->save($path);

        static::assertFileExists($path);
        static::assertXmlStringEqualsXmlFile($path, $this->manager->render('pages'));

        unlink($path); // Delete the saved file
    }

    /** @test */
    public function it_can_switch_sitemap_format_to_txt(): void
    {
        $this->manager->format('txt');

        static::assertNull($this->manager->render());
        static::assertNull($this->manager->render('pages'));
        static::assertNull($this->manager->render('blog'));

        $this->populatedManager();

        static::assertMatchesSnapshot($this->manager->render());
        static::assertMatchesSnapshot($this->manager->render('pages'));
        static::assertMatchesSnapshot($this->manager->render('blog'));
    }

    /** @test */
    public function it_can_switch_sitemap_format_to_rss(): void
    {
        $this->manager->format('rss');

        static::assertNull($this->manager->render());
        static::assertNull($this->manager->render('pages'));
        static::assertNull($this->manager->render('blog'));

        $this->populatedManager();

        static::assertMatchesXmlSnapshot($this->manager->render());
        static::assertMatchesXmlSnapshot($this->manager->render('pages'));
        static::assertMatchesXmlSnapshot($this->manager->render('blog'));
    }

    /** @test */
    public function it_must_return_null_if_format_is_invalid(): void
    {
        $this->populatedManager();

        $this->manager->format('php');

        static::assertNull($this->manager->render());
        static::assertNull($this->manager->render('pages'));
        static::assertNull($this->manager->render('blog'));
    }

    /** @test */
    public function it_can_chunk_a_huge_sitemap_on_render(): void
    {
        $this->manager->add('blog', $this->createBlogSitemap(499));

        $this->manager->format('txt');

        static::assertMatchesSnapshot($this->manager->render('blog'));

        foreach (range(1, 5) as $index) {
            static::assertTrue($this->manager->has("blog.$index"), "Issue with the index: $index");
            static::assertMatchesSnapshot($this->manager->render("blog.$index"));
        }

        static::assertFalse($this->manager->has('blog.6'));
        static::assertNull($this->manager->render('blog.6'));
    }

    /** @test */
    public function it_can_chunk_a_huge_sitemap_on_save(): void
    {
        $this->manager->add('blog', $this->createBlogSitemap(499));

        $directory = __DIR__.'/__temp__';

        $this->manager->save("{$directory}/sitemap-blog.xml");

        $expectations = [
            "{$directory}/sitemap-blog.xml",
            "{$directory}/sitemap-blog-1.xml",
            "{$directory}/sitemap-blog-2.xml",
            "{$directory}/sitemap-blog-3.xml",
            "{$directory}/sitemap-blog-4.xml",
            "{$directory}/sitemap-blog-5.xml",
        ];

        foreach ($expectations as $expected) {
            static::assertFileExists($expected);

            unlink($expected); // Delete the file
        }
    }

    /** @test */
    public function it_can_respond_with_http_response(): void
    {
        $response = $this->manager->respond();

        static::assertInstanceOf(Response::class, $response);

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
        static::assertSame('', $response->getContent());
        static::assertSame('application/xml', $response->headers->get('content-type'));

        $this->populatedManager();

        $response = $this->manager->respond();

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
        static::assertMatchesXmlSnapshot($response->getContent());
        static::assertSame('application/xml', $response->headers->get('content-type'));

        $response = $this->manager->format('txt')->respond();

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
        static::assertMatchesSnapshot($response->getContent());
        static::assertSame('text/plain', $response->headers->get('content-type'));

        $response = $this->manager->format('rss')->respond('blog');

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
        static::assertMatchesSnapshot($response->getContent());
        static::assertSame('application/rss+xml', $response->headers->get('content-type'));
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Populate the manager with sitemaps.
     */
    private function populatedManager(): void
    {
        $this->manager->add('pages', $this->createPagesSitemap());
        $this->manager->add('blog', $this->createBlogSitemap());
    }
}
