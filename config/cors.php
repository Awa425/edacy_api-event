<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // en dev, ou spécifie l'origine exacte
    'allowed_headers' => ['*'],
    'supports_credentials' => true,
];