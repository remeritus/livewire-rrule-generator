<?php

return [
    'title'         => 'Define Schedules',

    'includeWeekend' => TRUE,
    'frequencies' => [
        'SECONDLY'  => FALSE,
        'MINUTELY'  => FALSE,
        'HOURLY'    => FALSE,
        'DAILY'     => TRUE,
        'WEEKLY'    => TRUE,
        'MONTHLY'   => TRUE,
        'YEARLY'    => FALSE,
    ],

    'defaultView'    => 'WEEKLY',
    'modelsLocation'    => 'App\\Models\\'
];
