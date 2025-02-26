<?php

return [
    'ttl' => 60 * 24 * 7, // 1 week
    'route' => env('COMUNI_ROUTE', 'api/comuni'),
    'middlewares' => [
        'api'
    ],
    'import'=> [
        'zip_codes_file' => 'https://raw.githubusercontent.com/axiostudio/comuni-italiani/main/data/import/json/gi_cap.json',
        'province_codes_file' => 'https://raw.githubusercontent.com/axiostudio/comuni-italiani/main/data/import/json/gi_province.json',
        'comuni_data_file'    => 'https://raw.githubusercontent.com/axiostudio/comuni-italiani/main/data/import/json/gi_comuni.json',
        'regioni_data_file'   => 'https://raw.githubusercontent.com/axiostudio/comuni-italiani/main/data/import/json/gi_regioni.json',
        'zone_data_file'      => 'https://raw.githubusercontent.com/axiostudio/comuni-italiani/main/data/import/json/gi_zone.json',
    ]
];
