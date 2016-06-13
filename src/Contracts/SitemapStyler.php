<?php namespace Arcanedev\LaravelSitemap\Contracts;

/**
 * Interface  SitemapStyler
 *
 * @package   Arcanedev\LaravelSitemap\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface SitemapStyler
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants 
     | ------------------------------------------------------------------------------------------------
     */
    const GOOGLE_NEWS_FORMAT  = 'google-news';
    const MOBILE_FORMAT       = 'mobile';
    const SITEMAPINDEX_FORMAT = 'sitemapindex';
    const XML_FORMAT          = 'xml';
    
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the styler is enabled (Get the enabled status).
     *
     * @return bool
     */
    public function isEnabled();

    /**
     * Set the enabled status.
     *
     * @param  bool  $status
     *
     * @return bool
     */
    public function setEnabled($status);

    /**
     * Get the location for xsl styles.
     *
     * @return string|null
     */
    public function getLocation();

    /**
     * Set the location for xsl styles.
     *
     * @param  string  $location
     *
     * @return self
     */
    public function setLocation($location);

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the sitemap style.
     *
     * @param  string       $format
     * @param  string|null  $style
     *
     * @return string|null
     */
    public function get($format, $style);

    /**
     * Enable the sitemap styles.
     *
     * @return bool
     */
    public function enable();

    /**
     * Disable the sitemap styles.
     *
     * @return bool
     */
    public function disable();
}
