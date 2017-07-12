<?php

use Arcanedev\LaravelSitemap\Contracts\SitemapFrequency;

return [

    /* -----------------------------------------------------------------
     |  Frequencies
     | -----------------------------------------------------------------
     */

    SitemapFrequency::ALWAYS  => 'Always',
    SitemapFrequency::HOURLY  => 'Hourly',
    SitemapFrequency::DAILY   => 'Daily',
    SitemapFrequency::WEEKLY  => 'Weekly',
    SitemapFrequency::MONTHLY => 'Monthly',
    SitemapFrequency::YEARLY  => 'Yearly',
    SitemapFrequency::NEVER   => 'Never',

];
