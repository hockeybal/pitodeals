<?php

return [
    'strapi' => [
        'enabled' => (bool) env('STRAPI_ENABLED', false),
        'url' => rtrim((string) env('STRAPI_URL', ''), '/'),
        'token' => env('STRAPI_TOKEN'),
        'content_endpoint' => env('STRAPI_CONTENT_ENDPOINT', '/api/pito-web'),
        'timeout' => (int) env('STRAPI_TIMEOUT', 5),
        'cache_seconds' => (int) env('STRAPI_CACHE_SECONDS', 300),
    ],
    'live_municipalities' => [
        'amersfoort' => 'rich',
        'woerden' => 'live',
        'ede' => 'live',
        'veenendaal' => 'live',
        'nijkerk' => 'live',
        'barneveld' => 'live',
    ],
    'external' => [
        'app_download' => env('PITO_APP_DOWNLOAD_URL', 'https://pito.app'),
        'business' => env('PITO_BUSINESS_URL', 'https://pito.app/nl/zakelijk'),
        'business_signup' => env('PITO_BUSINESS_SIGNUP_URL', 'https://pito.app/signup'),
        'business_login' => env('PITO_BUSINESS_LOGIN_URL', 'https://pito.app/nl/login'),
    ],
];
