<?php

use Arcanedev\LaravelSitemap\Contracts\Entities\ChangeFrequency;

return [

    ChangeFrequency::ALWAYS  => 'Always',
    ChangeFrequency::HOURLY  => 'Hourly',
    ChangeFrequency::DAILY   => 'Daily',
    ChangeFrequency::WEEKLY  => 'Weekly',
    ChangeFrequency::MONTHLY => 'Monthly',
    ChangeFrequency::YEARLY  => 'Yearly',
    ChangeFrequency::NEVER   => 'Never',

];
