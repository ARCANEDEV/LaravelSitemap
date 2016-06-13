<?php namespace Arcanedev\LaravelSitemap\Tests\Entities;

use Arcanedev\LaravelSitemap\Entities\SitemapItemCollection;
use Arcanedev\LaravelSitemap\Tests\TestCase;

/**
 * Class     SitemapItemCollectionTest
 *
 * @package  Arcanedev\LaravelSitemap\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapItemCollectionTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  \Arcanedev\LaravelSitemap\Entities\SitemapItemCollection */
    private $items;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->items = new SitemapItemCollection();
    }

    public function tearDown()
    {
        unset($this->items);

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
            \Arcanedev\LaravelSitemap\Entities\SitemapItemCollection::class,
            $this->items
        );

        $this->assertCount(0, $this->items);
    }
}
