<?php namespace Arcanedev\LaravelSitemap\Entities;

use Arcanedev\Support\Collection;

/**
 * Class     SitemapItemCollection
 *
 * @package  Arcanedev\LaravelSitemap\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapItemCollection extends Collection
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Add a sitemap item to the collection.
     *
     * @param  array  $params
     * @param  bool   $escape
     *
     * @return self
     */
    public function addItem(array $params, $escape = true)
    {
        $this->push(SitemapItem::make($params, $escape));

        return $this;
    }

    /**
     * Get a sitemap item from the collection by key.
     *
     * @param  mixed  $key
     * @param  mixed  $default
     *
     * @return \Arcanedev\LaravelSitemap\Entities\SitemapItem|null
     */
    public function get($key, $default = null)
    {
        return parent::get($key, $default);
    }
}
