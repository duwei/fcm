<?php

return [

    'connections' => [
        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('MSSQL_DATABASE_URL'),
            'host' => env('MSSQL_DB_HOST', 'localhost'),
            'port' => env('MSSQL_DB_PORT', '1433'),
            'database' => env('MSSQL_DB_DATABASE', 'forge'),
            'username' => env('MSSQL_DB_USERNAME', 'forge'),
            'password' => env('MSSQL_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'fetch'       => PDO::FETCH_ASSOC,
        ],
    ],

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
//            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
            'queue' => '{default}',
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],
    ],

];
