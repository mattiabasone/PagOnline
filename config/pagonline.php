<?php

return [
    'server_url' => env('PAGONLINE_SERVER_URL', null),
    'timeout' => env('PAGONLINE_TIMEOUT', 15000),
    // tid
    'terminal_id' => env('PAGONLINE_TERMINAL_ID', null),
    // kSig
    'signature_key' => env('PAGONLINE_SIGNATURE_KEY', null),
    'currency' => env('PAGONLINE_CURRENCY', 'EUR'),
    'language' => env('PAGONLINE_LANGUAGE', 'IT'),
];
