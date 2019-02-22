<?php

return [
    'server_url' => env('PAGONLINE_SERVER_URL', null),
    'request_timeout' => env('PAGONLINE_REQUEST_TIMEOUT', 15),
    'connect_timeout' => env('PAGONLINE_CONNECT_TIMEOUT', 5),

    'http_proxy' => env('PAGONLINE_HTTP_PROXY', ''),
    'http_basic_auth_user' => env('PAGONLINE_HTTP_BASIC_AUTH_USER', ''),
    'http_basic_auth_pass' => env('PAGONLINE_HTTP_BASIC_AUTH_PASS', ''),

    // tid
    'terminal_id' => env('PAGONLINE_TERMINAL_ID', null),
    // kSig
    'signature_key' => env('PAGONLINE_SIGNATURE_KEY', null),
    'currency_code' => env('PAGONLINE_CURRENCY_CODE', 'EUR'),
    'language_id' => env('PAGONLINE_LANGUAGE_ID', 'IT'),
];
