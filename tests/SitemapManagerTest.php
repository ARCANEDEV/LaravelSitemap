<?php namespace Arcanedev\LaravelSitemap\Tests;

use Arcanedev\LaravelSitemap\Entities\Sitemap;
use Arcanedev\LaravelSitemap\Entities\Url;
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

    protected function setUp()
    {
        parent::setUp();

        $this->manager = $this->app->make(\Arcanedev\LaravelSitemap\Contracts\SitemapManager::class);
    }

    protected function tearDown()
    {
        unset($this->manager);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Arcanedev\LaravelSitemap\Contracts\SitemapManager::class,
            \Arcanedev\LaravelSitemap\SitemapManager::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->manager);
        }
    }

    /** @test */
    public function it_should_return_empty_sitemaps_collection_on_creation()
    {
        $this->assertCount(0, $this->manager->all());
        $this->assertSame(0, $this->manager->count());
    }

    /** @test */
    public function it_can_add_a_sitemap_to_collection()
    {
        $this->manager->add('pages', $sitemap = new Sitemap);

        $this->assertSame(1, $this->manager->count());
        $this->assertCount(1, $sitemaps = $this->manager->all());

        $this->assertSame($sitemap, $sitemaps->first());
    }

    /** @test */
    public function it_can_get_a_sitemap_by_its_name()
    {
        $this->manager->add('blog', $sitemap = new Sitemap);

        $this->assertSame(1, $this->manager->count());
        $this->assertCount(1, $sitemaps = $this->manager->all());

        $sitemap = $this->manager->get('blog');

        $this->assertInstanceOf(\Arcanedev\LaravelSitemap\Entities\Sitemap::class, $sitemap);
    }

    /** @test */
    public function it_can_create_and_add_a_sitemap_to_collection()
    {
        $this->manager->create('pages', function (Sitemap $sitemap) {
            $sitemap->add(Url::make('http://example.com'));
        });

        $sitemap = $this->manager->get('pages');

        $this->assertSame(1, $sitemap->count());

        $this->assertTrue($sitemap->has('http://example.com'));
        $this->assertFalse($sitemap->has('http://example.com/blog'));
    }

    /** @test */
    public function it_can_forget_sitemaps_from_collection()
    {
        $this->populatedManager();

        $this->assertCount(2, $this->manager);
        $this->assertTrue($this->manager->has('pages'));
        $this->assertTrue($this->manager->has('blog'));

        $this->manager->forget('blog');
    }

    /** @test */
    public function it_can_convert_to_array()
    {
        $this->assertSame([], $this->manager->toArray());

        $this->populatedManager();

        $result = $this->manager->toArray();

        $this->assertArrayHasKeys(['pages', 'blog'], $result);

        foreach ($result as $sitemapUrls) {
            foreach ($sitemapUrls as $url) {
                $this->assertArrayHasKeys(['loc', 'lastmod', 'changefreq', 'priority'], $url);
            }
        }
    }

    /** @test */
    public function it_can_convert_to_json()
    {
        $expected = '[]';

        $this->assertSame($expected, json_encode($this->manager));
        $this->assertSame($expected, $this->manager->toJson());

        $this->populatedManager();

        $expected = json_encode($this->manager->toArray());

        $this->assertSame($expected, json_encode($this->manager));
        $this->assertSame($expected, $this->manager->toJson());
    }


    /** @test */
    public function it_can_render_sitemaps()
    {
        $this->populatedManager();

        $this->assertMatchesXmlSnapshot($this->manager->render());
        $this->assertMatchesXmlSnapshot($this->manager->render('pages'));
        $this->assertMatchesXmlSnapshot($this->manager->render('blog'));

        $this->assertNull($this->manager->render('admin')); // Not available
    }

    /** @test */
    public function it_should_return_null_when_rendering_an_empty_manager()
    {
        $this->assertNull($this->manager->render());
    }

    /** @test */
    public function it_can_save_sitemap_index()
    {
        $directory = __DIR__.'/__temp__';
        $this->manager->save($path = "$directory/sitemap.xml");

        $this->assertFileNotExists($path);

        $this->populatedManager();

        $this->manager->save($path);

        $this->assertFileExists($path);
        $this->assertXmlStringEqualsXmlFile($path, $this->manager->render());

        unlink($path);
    }

    /** @test */
    public function it_can_save_sitemaps()
    {
        $directory = __DIR__.'/__temp__';

        foreach (['pages', 'blog'] as $name) {
            $this->assertFileNotExists($path = "{$directory}/sitemap-{$name}.xml");

            $this->populatedManager();

            $this->manager->save($path, $name);

            $this->assertFileExists($path);
            $this->assertXmlStringEqualsXmlFile($path, $this->manager->render($name));

            unlink($path); // Delete the saved file
        }
    }

    /** @test */
    public function it_can_save_the_single_sitemap_instead_of_index()
    {
        $directory = __DIR__.'/__temp__';
        $path = "$directory/sitemap.xml";

        $this->manager->add('pages', $this->createPagesSitemap());

        $this->manager->save($path);

        $this->assertFileExists($path);
        $this->assertXmlStringEqualsXmlFile($path, $this->manager->render('pages'));

        unlink($path); // Delete the saved file
    }

    /** @test */
    public function it_can_switch_sitemap_format_to_txt()
    {
        $this->manager->format('txt');

        $this->assertNull($this->manager->render());
        $this->assertNull($this->manager->render('pages'));
        $this->assertNull($this->manager->render('blog'));

        $this->populatedManager();

        $this->assertMatchesSnapshot($this->manager->render());
        $this->assertMatchesSnapshot($this->manager->render('pages'));
        $this->assertMatchesSnapshot($this->manager->render('blog'));
    }

    /** @test */
    public function it_can_switch_sitemap_format_to_rss()
    {
        $this->manager->format('rss');

        $this->assertNull($this->manager->render());
        $this->assertNull($this->manager->render('pages'));
        $this->assertNull($this->manager->render('blog'));

        $this->populatedManager();

        $this->assertMatchesXmlSnapshot($this->manager->render());
        $this->assertMatchesXmlSnapshot($this->manager->render('pages'));
        $this->assertMatchesXmlSnapshot($this->manager->render('blog'));
    }

    /** @test */
    public function it_must_return_null_if_format_is_invalid()
    {
        $this->populatedManager();

        $this->manager->format('php');

        $this->assertNull($this->manager->render());
        $this->assertNull($this->manager->render('pages'));
        $this->assertNull($this->manager->render('blog'));
    }

    /** @test */
    public function it_can_chunk_a_huge_sitemap_on_render()
    {
        $this->manager->add('blog', $this->createBlogSitemap(499));

        $this->manager->format('txt');

        $this->assertMatchesSnapshot($this->manager->render('blog'));

        foreach (range(1, 5) as $index) {
            $this->assertMatchesSnapshot($this->manager->render("blog.$index"));
        }

        $this->assertNull($this->manager->render('blog.6'));
    }

    /** @test */
    public function it_can_chunk_a_huge_sitemap_on_save()
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
            $this->assertFileExists($expected);

            unlink($expected); // Delete the file
        }
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Populate the manager with sitemaps.
     */
    private function populatedManager()
    {
        $this->manager->add('pages', $this->createPagesSitemap());
        $this->manager->add('blog', $this->createBlogSitemap());
    }
}
