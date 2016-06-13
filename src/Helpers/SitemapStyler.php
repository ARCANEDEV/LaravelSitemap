<?php namespace Arcanedev\LaravelSitemap\Helpers;

use Arcanedev\LaravelSitemap\Contracts\SitemapStyler as SitemapStylerContract;

/**
 * Class     SitemapStyler
 *
 * @package  Arcanedev\LaravelSitemap\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapStyler implements SitemapStylerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const VENDOR_PATH = 'vendor/sitemap';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The enabled status.
     *
     * @var  bool
     */
    protected $enabled = true;

    /**
     * The location for xsl styles (must end with slash).
     *
     * @var string|null
     */
    private $location;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * SitemapStyler constructor.
     */
    public function __construct()
    {
        //
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the styler is enabled (Get the enabled status).
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set the enabled status.
     *
     * @param  bool  $status
     *
     * @return bool
     */
    public function setEnabled($status)
    {
        $this->enabled = (bool) $status;

        return $this;
    }

    /**
     * Get the location for xsl styles.
     *
     * @return string|null
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set the location for xsl styles.
     *
     * @param  string  $location
     *
     * @return self
     */
    public function setLocation($location)
    {
        if (is_string($location) && ! empty($location)) {
            $location .= substr($location, -1) === '/' ? '' : '/';
        }

        $this->location = $location;

        return $this;
    }

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
    public function get($format, $style = null)
    {
        return $this->isEnabled() ? $this->getStyle($format, $style) : null;
    }

    /**
     * Enable the sitemap styles.
     *
     * @return bool
     */
    public function enable()
    {
        return $this->setEnabled(true);
    }

    /**
     * Disable the sitemap styles.
     *
     * @return bool
     */
    public function disable()
    {
        return $this->setEnabled(false);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the sitemap style (ugly stuff here).
     *
     * @param  string       $format
     * @param  string|null  $style
     *
     * @return string|null
     */
    protected function getStyle($format, $style)
    {
        if ( ! is_null($style) && $this->hasPublicFile($style)) {
            return $style;
        }

        // Use style from a custom location
        if ($this->hasLocation() && $this->hasPublicFile($this->fromLocation($format))) {
            return $this->fromLocation($format);
        }

        // Use the published vendor style
        if ($this->hasPublicFile(self::VENDOR_PATH . "/styles/$format.xsl")) {
            return asset(self::VENDOR_PATH . "/styles/$format.xsl");
        }

        return null;
    }

    /**
     * Get the format location.
     *
     * @param  string  $format
     *
     * @return string
     */
    protected function fromLocation($format)
    {
        return $this->getLocation()."$format.xsl";
    }

    /**
     * Check if the location is set.
     *
     * @return bool
     */
    protected function hasLocation()
    {
        return ! is_null($this->getLocation());
    }

    /**
     * Check if the public file exists.
     *
     * @param  string  $path
     *
     * @return bool
     */
    protected function hasPublicFile($path)
    {
        return file_exists(public_path($path));
    }
}
