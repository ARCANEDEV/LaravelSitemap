<?php namespace Arcanedev\LaravelSitemap\Entities;

use Arcanedev\LaravelSitemap\Contracts\Entities\Url as UrlContract;
use Arcanedev\LaravelSitemap\Exceptions\SitemapException;
use ArrayAccess;
use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class     Url
 *
 * @package  Arcanedev\LaravelSitemap\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Url implements ArrayAccess, UrlContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  string  */
    protected $loc;

    /** @var  string|null */
    protected $title;

    /** @var  \DateTimeInterface */
    protected $lastModDate;

    /** @var  string */
    protected $changeFrequency;

    /** @var  float */
    protected $priority;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Url constructor.
     *
     * @param  array|string  $attributes
     */
    public function __construct($attributes)
    {
        if (is_string($attributes))
            $attributes = ['loc' => $attributes];

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
        return $this->escape($this->loc);
    }

    /**
     * Get the url location (alias).
     *
     * @see getLoc()
     *
     * @return string
     */
    public function loc()
    {
        return $this->getLoc();
    }

    /**
     * Set the url location.
     *
     * @param  string  $loc
     *
     * @return self
     *
     * @throws \Arcanedev\LaravelSitemap\Exceptions\SitemapException
     */
    public function setLoc($loc)
    {
        $this->loc = $this->checkLoc($loc);

        return $this;
    }

    /**
     * Get the last modification date.
     *
     * @return \DateTimeInterface
     */
    public function getLastMod()
    {
        return $this->lastModDate;
    }

    /**
     * Get the last modification date (alias).
     *
     * @see getLastMod()
     *
     * @return \DateTimeInterface
     */
    public function lastMod()
    {
        return $this->getLastMod();
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

        $this->lastModDate = $lastModDate;

        return $this;
    }

    /**
     * Get the change frequency.
     *
     * @return string
     */
    public function getChangeFreq()
    {
        return $this->changeFrequency;
    }

    /**
     * Get the change frequency (alias).
     *
     * @see getChangeFreq()
     *
     * @return string
     */
    public function changeFreq()
    {
        return $this->getChangeFreq();
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
        $this->changeFrequency = strtolower(trim($changeFreq));

        return $this;
    }

    /**
     * Get the priority.
     *
     * @return float
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Get the priority (alias).
     *
     * @see getPriority()
     *
     * @return float
     */
    public function priority()
    {
        return $this->getPriority();
    }

    /**
     * Set the priority.
     *
     * @param  float  $priority
     *
     * @return self
     *
     * @throws \Arcanedev\LaravelSitemap\Exceptions\SitemapException
     */
    public function setPriority($priority)
    {
        $this->priority = $this->checkPriority($priority);

        return $this;
    }

    /**
     * Get the title.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->escape($this->title);
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
        $this->title = $title;

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
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'title'      => $this->getTitle(),
            'loc'        => $this->getLoc(),
            'lastmod'    => $this->formatLastMod(),
            'changefreq' => $this->getChangeFreq(),
            'priority'   => $this->getPriority(),
        ];
    }

    /**
     * Get the sitemap url as JSON.
     *
     * @param  int  $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
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
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return method_exists($this, 'get'.Str::studly($offset));
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return call_user_func([$this, 'get'.Str::studly($offset)]);
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     *
     * @return void
     */
    public function offsetSet($offset, $value) {} // Do nothing...

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     *
     * @return void
     */
    public function offsetUnset($offset) {} // Do nothing...

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
