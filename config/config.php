<?php

return [
    'ttl' => 60 * 24 * 7, // 1 week
    'route' => env('COMUNI_ROUTE', 'api/comuni'),
    'middlewares' => [
        'api',
    ],
    'import' => [
        'zone_data_file' =>  dirname(__DIR__).'/data/import/json/gi_zone.json',
        'regioni_data_file' => dirname(__DIR__).'/data/import/json/gi_regioni.json',
        'province_data_file' => dirname(__DIR__).'/data/import/json/gi_province.json',
        'comuni_data_file' => dirname(__DIR__).'/data/import/json/gi_comuni.json',
        'zip_data_file' => dirname(__DIR__).'/data/import/json/gi_cap.json',
    ],
];