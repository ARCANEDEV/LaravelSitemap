<?php namespace Arcanedev\LaravelSitemap\Contracts;

/**
 * Interface  SitemapGenerator
 *
 * @package   Arcanedev\LaravelSitemap\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface SitemapGenerator
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the sitemap styles location.
     *
     * @return string
     */
    public function getStylesLocation();

    /**
     * Set the sitemap styles location.
     *
     * @param  string  $location
     *
     * @return self
     */
    public function setStylesLocation($location);

    /**
     * Set the use styles value.
     *
     * @param  bool  $useStyles
     *
     * @return self
     */
    public function setUseStyles($useStyles);

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * @param  array        $data
     * @param  string       $format
     * @param  string|null  $style
     *
     * @return array
     */
    public function generate(array $data, $format = 'xml', $style = null);

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check is styles is enabled.
     *
     * @return bool
     */
    public function isStylesEnabled();
}
