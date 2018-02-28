<?php namespace Arcanedev\LaravelSitemap\Entities;

use Arcanedev\LaravelSitemap\Contracts\Entities\Url as UrlContract;
use Arcanedev\LaravelSitemap\Exceptions\SitemapException;
use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

/**
 * Class     Url
 *
 * @package  Arcanedev\LaravelSitemap\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Url extends Fluent implements UrlContract
{
    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Url constructor.
     *
     * @param  array|string  $attributes
     */
    public function __construct($attributes = [])
    {
        if (is_string($attributes))
            $attributes = ['loc' => $attributes];

        parent::__construct($attributes);

        $this->setLoc(Arr::get($attributes, 'loc'));
        $this->setLastMod(Arr::get($attributes, 'lastmod', new DateTime));
        $this->setChangeFreq(Arr::get($attributes, 'changefreq', ChangeFrequency::DAILY));
        $this->setPriority(Arr::get($attributes, 'priority', 0.8));
        $this->setTitle(Arr::get($attributes, 'title'));
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the url location.
     *
     * @return string
     */
    public function getLoc()
    {
        return $this->escape($this->get('loc'));
    }

    /**
     * Set the url location.
     *
     * @param  string  $loc
     *
     * @return self
     */
    public function setLoc($loc)
    {
        return $this->set('loc', $this->checkLoc($loc));
    }

    /**
     * Get the last modification date.
     *
     * @return \DateTimeInterface
     */
    public function getLastMod()
    {
        return $this->get('lastmod');
    }

    /**
     * Format the url last modification.
     *
     * @param  string  $format
     *
     * @return string
     */
    public function formatLastMod($format = DateTime::ATOM)
    {
        return $this->getLastMod()->format($format);
    }

    /**
     * Set the last modification date.
     *
     * @param  string|\DateTimeInterface  $lastModDate
     * @param  string                     $format
     *
     * @return self
     */
    public function setLastMod($lastModDate, $format = 'Y-m-d H:i:s')
    {
        if (is_string($lastModDate))
            $lastModDate = DateTime::createFromFormat($format, $lastModDate);

        return $this->set('lastmod', $lastModDate);
    }

    /**
     * Get the change frequency.
     *
     * @return string
     */
    public function getChangeFreq()
    {
        return $this->get('changefreq');
    }

    /**
     * Set the change frequency.
     *
     * @param  string  $changeFreq
     *
     * @return self
     */
    public function setChangeFreq($changeFreq)
    {
        return $this->set('changefreq', strtolower(trim($changeFreq)));
    }

    /**
     * Get the priority.
     *
     * @return float
     */
    public function getPriority()
    {
        return $this->get('priority');
    }

    /**
     * Set the priority.
     *
     * @param  float  $priority
     *
     * @return self
     */
    public function setPriority($priority)
    {
        $priority = $this->checkPriority($priority);

        return $this->set('priority', $priority);
    }

    /**
     * Get the title.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->escape($this->get('title'));
    }

    /**
     * Get the title.
     *
     * @param  string  $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        return $this->set('title', $title);
    }

    /**
     * Set an attribute.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return self
     */
    public function set($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a sitemap url instance.
     *
     * @param  string  $loc
     *
     * @return \Arcanedev\LaravelSitemap\Entities\Url
     */
    public static function make($loc)
    {
        return new static(compact('loc'));
    }

    /**
     * Make a URL instance with attributes.
     *
     * @param  array  $attributes
     *
     * @return \Arcanedev\LaravelSitemap\Entities\Url
     */
    public static function makeFromArray(array $attributes)
    {
        return new static($attributes);
    }

    /**
     * Check if has an attribute.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function has($key)
    {
        return ! is_null($this->get($key));
    }

    /**
     * Convert the Fluent instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'lastmod' => $this->formatLastMod(),
        ]);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Escape the given value.
     *
     * @param  string  $value
     *
     * @return string
     */
    protected function escape($value)
    {
        if (is_null($value))
            return $value;

        return config('sitemap.escaping', true)
            ? htmlentities($value, ENT_XML1, 'UTF-8')
            : $value;
    }

    /**
     * Check the loc value.
     *
     * @param  string  $loc
     *
     * @return string
     *
     * @throws \Arcanedev\LaravelSitemap\Exceptions\SitemapException
     */
    protected function checkLoc($loc)
    {
        if (is_null($loc) || ! is_string($loc))
            throw new SitemapException('The [loc] attribute is required and must be string value.');

        return $loc;
    }

    /**
     * Check the priority value.
     *
     * @param  float  $priority
     *
     * @return float
     *
     * @throws \Arcanedev\LaravelSitemap\Exceptions\SitemapException
     */
    protected function checkPriority($priority)
    {
        if ( ! is_numeric($priority))
            throw new SitemapException("The [priority] value must be numeric.");

        $priority = round($priority, 1);

        if ($priority > 1 || $priority < 0)
            throw new SitemapException("The [priority] value must be between `0.0` and `1.0`, `{$priority}` was given.");

        return $priority;
    }
}
