<?php

return [
    'route' => env('COMUNI_ROUTE', 'api/comuni'),
    'middlewares' => ['api'],
    'ttl' => 60 * 24 * 7, // 1 week
    'data' => 'https://raw.githubusercontent.com/axiostudio/comuni-italiani/main/data/latest.json',
];
