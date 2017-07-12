<?php

use Arcanedev\LaravelSitemap\Contracts\SitemapFrequency;

return [

    /* -----------------------------------------------------------------
     |  Frequencies
     | -----------------------------------------------------------------
     */

    SitemapFrequency::ALWAYS  => 'Toujours',
    SitemapFrequency::HOURLY  => 'Une fois par heure',
    SitemapFrequency::DAILY   => 'Quotidien',
    SitemapFrequency::WEEKLY  => 'Hebdomadaire',
    SitemapFrequency::MONTHLY => 'Mensuel',
    SitemapFrequency::YEARLY  => 'Annuel',
    SitemapFrequency::NEVER   => 'Jamais',

];
