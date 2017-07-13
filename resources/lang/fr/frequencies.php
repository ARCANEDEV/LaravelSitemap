<?php

use Arcanedev\LaravelSitemap\Contracts\Entities\ChangeFrequency;

return [

    ChangeFrequency::ALWAYS  => 'Toujours',
    ChangeFrequency::HOURLY  => 'Une fois par heure',
    ChangeFrequency::DAILY   => 'Quotidien',
    ChangeFrequency::WEEKLY  => 'Hebdomadaire',
    ChangeFrequency::MONTHLY => 'Mensuel',
    ChangeFrequency::YEARLY  => 'Annuel',
    ChangeFrequency::NEVER   => 'Jamais',

];
