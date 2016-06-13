<?php namespace Arcanedev\LaravelSitemap\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class     Sitemap
 *
 * @package  Arcanedev\LaravelSitemap\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Sitemap extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'sitemap'; }
}
