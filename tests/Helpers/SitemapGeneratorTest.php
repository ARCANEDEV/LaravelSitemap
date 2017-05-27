<?php namespace Arcanedev\LaravelSitemap\Tests\Helpers;

use Arcanedev\LaravelSitemap\Contracts\SitemapGenerator as SitemapGeneratorContract;
use Arcanedev\LaravelSitemap\Tests\TestCase;

/**
 * Class     SitemapGeneratorTest
 *
 * @package  Arcanedev\LaravelSitemap\Tests\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapGeneratorTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var \Arcanedev\LaravelSitemap\Helpers\SitemapGenerator */
    private $generator;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->generator = $this->app->make(SitemapGeneratorContract::class);
    }

    public function tearDown()
    {
        unset($this->generator);

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
            \Arcanedev\LaravelSitemap\Contracts\SitemapGenerator::class,
            \Arcanedev\LaravelSitemap\Helpers\SitemapGenerator::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->generator);
        }
    }

    /** @test */
    public function it_can_get_and_set_styles_location()
    {
        $this->assertEquals('', $this->generator->getStylesLocation());

        $path = public_path('vendor/sitemap/styles/');
        $this->generator->setStylesLocation($path);

        $this->assertEquals($path, $this->generator->getStylesLocation());
    }

    /** @test */
    public function it_can_enable_and_disable_styles()
    {
        $this->assertTrue($this->generator->isStylesEnabled());

        $this->generator->setUseStyles(false);

        $this->assertFalse($this->generator->isStylesEnabled());

        $this->generator->setUseStyles(true);

        $this->assertTrue($this->generator->isStylesEnabled());
    }

    /** @test */
    public function it_can_generate()
    {
        $expectations = [
            'xml'     => [
                'data' => [
                    'items' => [],
                ],
                'headers' => [
                    'Content-type' => 'text/xml; charset=utf-8',
                ],
                'content' => [
                    '<?xml version="1.0" encoding="UTF-8"?>',
                    '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">',
                    '</urlset>',
                ],
            ],
            'html'    => [
                'data' => [
                    'items'   => [],
                    'channel' => [
                        'title' => 'Sitemap title',
                        'link'  => $this->baseUrl,
                    ],
                ],
                'headers' => [
                    'Content-type' => 'text/html',
                ],
                'content' => [
                    '<title>Sitemap title</title>',
                    '<h1><a href="'.$this->baseUrl.'">Sitemap title</a></h1>',
                ],
            ],
            'ror-rdf' => [
                'data' => [
                    'items'   => [],
                    'channel' => [
                        'title' => 'Sitemap title',
                        'link'  => $this->baseUrl,
                    ],
                ],
                'headers' => [
                    'Content-type' => 'text/rdf+xml; charset=utf-8',
                ],
                'content' => [
                    '<?xml version="1.0" encoding="UTF-8"?>',
                    '<rdf:RDF xmlns="http://rorweb.com/0.1/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">',
                        '<Resource rdf:about="sitemap">',
                            '<title>Sitemap title</title>',
                            '<url>'.$this->baseUrl.'</url>',
                            '<type>sitemap</type>',
                        '</Resource>',
                    '</rdf:RDF>',
                ],
            ],
            'ror-rss' => [
                'data' => [
                    'items'   => [],
                    'channel' => [
                        'title' => 'Sitemap title',
                        'link'  => $this->baseUrl,
                    ],
                ],
                'headers' => [
                    'Content-type' => 'text/rss+xml; charset=utf-8',
                ],
                'content' => [
                    '<rss version="2.0" xmlns:ror="http://rorweb.com/0.1/" >',
                        '<channel>',
                            '<title>Sitemap title</title>',
                            '<link>'.$this->baseUrl.'</link>',
                        '</channel>',
                    '</rss>',
                ],
            ],
            'txt'     => [
                'data' => [
                    'items'   => [],
                ],
                'headers' => [
                    'Content-type' => 'text/plain',
                ],
                'content' => [
                ],
            ]
        ];

        foreach ($expectations as $format => $expectation) {
            $generated = $this->generator->generate($expectation['data'], $format);

            $this->assertArrayHasKey('content', $generated);
            $this->assertArrayHasKey('headers', $generated);
            $this->assertEquals($expectation['headers'], $generated['headers']);
            foreach ($expectation['content'] as $content) {
                $this->assertContains($content, $generated['content'], 'Failed on '.$format);
            }
        }
    }
}
