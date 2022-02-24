<?php

return [
    'title'         => 'Define Schedule',
    'includeWeekend' => TRUE,
    'includeDTSTART' => TRUE,
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
    'weeekStarts'   => 'MO'
];
