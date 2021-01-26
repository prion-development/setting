<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Should we log setting changes in a table?
    |
    |   Supported Values: true, false
    |
    */

    'enable_logging' => true,

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Settings to store data in the cache?
    |
    */

    'cache' => [
        'enabled' => true, // true || false
        'tag' => "prion:setting",
        'ttl' => 60, // In Minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    |
    | This is where we will story settings data.
    |
    |   Supported Drivers: mysql
    |
    */
    'storage' => 'mysql',

    'database' => [
        'tables' => [
            'settings' => 'settings',

            'settings_log' => 'settings_log',
        ],
    ],
];