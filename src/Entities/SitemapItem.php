<?php namespace Arcanedev\LaravelSitemap\Entities;

use ArrayAccess;

/**
 * Class     SitemapItem
 *
 * @package  Arcanedev\LaravelSitemap\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SitemapItem implements ArrayAccess
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * URL of the page.
     *
     * @var string
     */
    protected $loc;

    /**
     * The date of last modification of the file.
     *
     * @var string
     */
    protected $lastmod;

    /**
     * How frequently the page is likely to change.
     * Valid values: always|hourly|daily|weekly|monthly|yearly|never
     *
     * @var string
     */
    protected $freq;

    /**
     * The priority of this URL relative to other URLs on your site.
     * Valid values range from 0.0 to 1.0
     *
     * @var string
     */
    protected $priority;

    /** @var string */
    protected $title;

    /** @var array */
    protected $images       = [];

    /** @var array */
    protected $videos       = [];

    /** @var array */
    protected $translations = [];

    /** @var array */
    protected $googlenews   = [];

    /** @var array */
    protected $alternates   = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * SitemapItem constructor.
     *
     * @param  array  $params
     * @param  bool   $escape
     */
    public function __construct(array $params, $escape = true)
    {
        $this->setLoc(array_get($params, 'loc', '/'));
        $this->setLastmod(array_get($params, 'lastmod'));
        $this->setPriority(array_get($params, 'priority'));
        $this->setFreq(array_get($params, 'freq'));
        $this->setTitle(array_get($params, 'title'));
        $this->setImages(array_get($params, 'images', []));
        $this->setVideos(array_get($params, 'videos', []));
        $this->setTranslations(array_get($params, 'translations', []));
        $this->setGooglenews(array_get($params, 'googlenews', []));
        $this->setAlternates(array_get($params, 'alternates', []));

        if ($escape) $this->escape();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @return string
     */
    public function getLoc()
    {
        return $this->loc;
    }

    /**
     * @param  string  $loc
     *
     * @return self
     */
    public function setLoc($loc)
    {
        $this->loc = $loc;

        return $this;
    }

    /**
     * @param  bool  $format
     *
     * @return string
     */
    public function getLastmod($format = true)
    {
        return ($format && $this->lastmod !== null)
            ? date('c', strtotime($this->lastmod))
            : $this->lastmod;
    }

    /**
     * @param  \DateTime|string  $lastmod
     *
     * @return self
     */
    public function setLastmod($lastmod)
    {
        if ($lastmod instanceof \DateTime) {
            $lastmod = $lastmod->format('Y-m-d H:i:s');
        }

        $this->lastmod = $lastmod;

        return $this;
    }

    /**
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param  string  $priority
     *
     * @return self
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return string
     */
    public function getFreq()
    {
        return $this->freq;
    }

    /**
     * @param  string  $freq
     *
     * @return self
     */
    public function setFreq($freq)
    {
        $this->freq = $freq;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param  string  $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param array $images
     *
     * @return self
     */
    public function setImages(array $images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return array
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * @param  array  $videos
     *
     * @return self
     */
    public function setVideos(array $videos)
    {
        $this->videos = $videos;

        return $this;
    }

    /**
     * @return array
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param  array  $translations
     *
     * @return self
     */
    public function setTranslations(array $translations)
    {
        $this->translations = $translations;

        return $this;
    }

    /**
     * @return array
     */
    public function getGooglenews()
    {
        return $this->googlenews;
    }

    /**
     * @param  array  $googlenews
     *
     * @return self
     */
    public function setGooglenews(array $googlenews)
    {
        $this->googlenews['sitename']         = array_get($googlenews, 'sitename', '');
        $this->googlenews['language']         = array_get($googlenews, 'language', 'en');
        $this->googlenews['publication_date'] = array_get($googlenews, 'publication_date', date('Y-m-d H:i:s'));

        return $this;
    }

    /**
     * @return array
     */
    public function getAlternates()
    {
        return $this->alternates;
    }

    /**
     * @param  array  $alternates
     *
     * @return self
     */
    public function setAlternates(array $alternates)
    {
        $this->alternates = $alternates;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  ArrayAccess Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Whether a offset exists
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param  mixed  $offset  An offset to check for.
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    /**
     * Offset to retrieve
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param  mixed  $offset  The offset to retrieve.
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $method = 'get'.ucfirst($offset);

        return method_exists($this, $method) ? $this->{$method}() : null;
    }

    /**
     * Offset to set
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param  mixed  $offset  The offset to assign the value to.
     * @param  mixed  $value   The value to set.
     */
    public function offsetSet($offset, $value)
    {
        $method = 'set'.ucfirst($offset);

        if (method_exists($this, $method)) {
            $this->{$method}($value);
        }
    }

    /**
     * Offset to unset
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param  mixed  $offset  The offset to unset.
     */
    public function offsetUnset($offset)
    {
        $this->offsetSet($offset, null);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make a new sitemap item.
     *
     * @param  array  $params
     * @param  bool   $escape
     *
     * @return self
     */
    public static function make(array $params, $escape = true)
    {
        return new self($params, $escape);
    }

    /**
     * Escaping the sitemap item.
     */
    public function escape()
    {
        $this->escapeLoc();
        $this->escapeTitle();
        $this->escapeImages();
        $this->escapeTranslations();
        $this->escapeAlternates();
        $this->escapeVideos();
        $this->escapeGooglenews();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function escapeLoc()
    {
        $this->setLoc(htmlentities($this->loc, ENT_XML1));
    }

    private function escapeTitle()
    {
        if ( ! is_null($this->title)) {
            $this->setTitle(htmlentities($this->title, ENT_XML1));
        }
    }

    private function escapeImages()
    {
        if ($this->images) {
            foreach ($this->images as $k => $image) {
                foreach ($image as $key => $value) {
                    $this->images[$k][$key] = htmlentities($value, ENT_XML1);
                }
            }
        }
    }

    private function escapeTranslations()
    {
        if ($this->translations) {
            foreach ($this->translations as $k => $translation) {
                foreach ($translation as $key => $value) {
                    $this->translations[$k][$key] = htmlentities($value, ENT_XML1);
                }
            }
        }
    }

    private function escapeAlternates()
    {
        if ($this->alternates) {
            foreach ($this->alternates as $k => $alternate) {
                foreach ($alternate as $key => $value) {
                    $this->alternates[$k][$key] = htmlentities($value, ENT_XML1);
                }
            }
        }
    }

    private function escapeVideos()
    {
        if ($this->videos) {
            foreach ($this->videos as $k => $video) {
                if ($video['title'])
                    $this->videos[$k]['title']       = htmlentities($video['title'], ENT_XML1);
                if ($video['description'])
                    $this->videos[$k]['description'] = htmlentities($video['description'], ENT_XML1);
            }
        }
    }

    private function escapeGooglenews()
    {
        if ($this->googlenews && isset($this->googlenews['sitename'])) {
            $this->googlenews['sitename'] = htmlentities($this->googlenews['sitename'], ENT_XML1);
        }
    }
}
