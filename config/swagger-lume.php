<?php

return [
    'api' => [
        'title' => 'Cygnus API',
    ],

    'routes' => [
        // Route for the interactive API documentation UI
        'api' => 'api',
        // Route for the OpenAPI specification file (api-docs.json)
        'docs' => 'api/docs.json',
        // Route for the OAuth2 callback
        'oauth2_callback' => 'api/oauth2-callback',
    ],

    'paths' => [
        // The path to the generated API documentation files
        'docs' => storage_path('api-docs'),
        // The path to the generated API documentation JSON file
        'docs_json' => 'api-docs.json',
        // The path to the API source code where annotations are used
        'annotations' => base_path('app'),
        // The path to the views for swagger-lume
        'views' => base_path('resources/views/vendor/swagger-lume'),
    ],

    // Set this to `true` to regenerate the documentation on every request
    'generate_always' => env('SWAGGER_GENERATE_ALWAYS', false),

    // Set this to your API's base URL
    'constants' => [
        'SWAGGER_LUME_CONST_HOST' => env('APP_URL', 'http://localhost:8080'),
    ],

    // Other settings
    'swagger_version' => '3.0',
    'proxy' => false,
];