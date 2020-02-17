<?php

declare(strict_types=1);

namespace Arcanedev\LaravelSitemap\Entities;

use Arcanedev\LaravelSitemap\Contracts\Entities\Url as UrlContract;
use Arcanedev\LaravelSitemap\Exceptions\SitemapException;
use DateTime;
use DateTimeInterface;
use Illuminate\Support\{Arr, Fluent};

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
    public function getLoc(): string
    {
        return $this->escape($this->get('loc'));
    }

    /**
     * Set the url location.
     *
     * @param  string  $loc
     *
     * @return $this
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
    public function getLastMod(): DateTimeInterface
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
    public function formatLastMod(string $format = DateTimeInterface::ATOM): string
    {
        return $this->getLastMod()->format($format);
    }

    /**
     * Set the last modification date.
     *
     * @param  string|\DateTimeInterface  $lastModDate
     * @param  string                     $format
     *
     * @return $this
     */
    public function setLastMod($lastModDate, string $format = 'Y-m-d H:i:s')
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
    public function getChangeFreq(): string
    {
        return $this->get('changefreq');
    }

    /**
     * Set the change frequency.
     *
     * @param  string  $changeFreq
     *
     * @return $this
     */
    public function setChangeFreq(string $changeFreq)
    {
        return $this->set('changefreq', strtolower(trim($changeFreq)));
    }

    /**
     * Get the priority.
     *
     * @return float
     */
    public function getPriority(): float
    {
        return $this->get('priority');
    }

    /**
     * Set the priority.
     *
     * @param  float|mixed  $priority
     *
     * @return $this
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
    public function getTitle(): ?string
    {
        return $this->escape($this->get('title'));
    }

    /**
     * Get the title.
     *
     * @param  string|null  $title
     *
     * @return $this
     */
    public function setTitle(?string $title)
    {
        return $this->set('title', $title);
    }

    /**
     * Set an attribute.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return $this
     */
    public function set(string $key, $value)
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
     * @return $this
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
     * @return $this
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
    public function has(string $key): bool
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
     * @param  string|mixed  $value
     *
     * @return string|null
     */
    protected function escape($value)
    {
        if (is_null($value))
            return $value;

        if (config('sitemap.escaping', true))
            $value = htmlentities($value, ENT_XML1, 'UTF-8');

        return $value;
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
    protected function checkLoc($loc): string
    {
        if ( ! is_string($loc))
            throw new SitemapException('The [loc] attribute is required and must be string value.');

        return $loc;
    }

    /**
     * Check the priority value.
     *
     * @param  float|mixed  $priority
     *
     * @return float
     *
     * @throws \Arcanedev\LaravelSitemap\Exceptions\SitemapException
     */
    protected function checkPriority($priority): float
    {
        if ( ! is_numeric($priority))
            throw new SitemapException("The [priority] value must be numeric.");

        $priority = round($priority, 1);

        if ($priority > 1 || $priority < 0)
            throw new SitemapException("The [priority] value must be between `0.0` and `1.0`, `{$priority}` was given.");

        return $priority;
    }
}
