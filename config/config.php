<?php

return [
    'route'       => env('COMUNI_ROUTE', 'api/comuni'),
    'middlewares' => ['api'],
    'ttl'         => 60 * 24 * 7, // 1 week
    'data'        => 'https://raw.githubusercontent.com/axiostudio/comuni-italiani/main/data/latest.json',
    'import'      => [
        'zip_codes_file'      => 'https://raw.githubusercontent.com/axiostudio/comuni-italiani/main/data/import/json/gi_comuni_cap.json',
        'province_codes_file' => 'https://raw.githubusercontent.com/axiostudio/comuni-italiani/main/data/import/json/gi_province.json',
        'comuni_data_file'    => 'https://raw.githubusercontent.com/axiostudio/comuni-italiani/main/data/import/json/gi_comuni.json',
        'regioni_data_file'   => 'https://raw.githubusercontent.com/axiostudio/comuni-italiani/main/data/import/json/gi_regioni.json',
        'regions_groups'      => [
            '1' => 'Nord-ovest',
            '2' => 'Nord-est',
            '3' => 'Centro',
            '4' => 'Sud',
            '5' => 'Isole',
        ],
    ],
];
