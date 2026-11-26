<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Driver WhatsApp
    |--------------------------------------------------------------------------
    |
    | log    = hanya mencatat ke log
    | bridge = mengirim melalui Node.js WhatsApp Bridge
    |
    */

    'driver' => env('WA_DRIVER', 'log'),

    'bridge' => [
        'url' => env(
            'WA_BRIDGE_URL',
            'http://127.0.0.1:3001/send'
        ),

        'token' => env(
            'WA_BRIDGE_TOKEN',
            ''
        ),

        'timeout' => 15,
    ],

    'log_file' => storage_path('logs/whatsapp.log'),

    'events' => [

        'peminjaman' => true,

        'pengembalian' => true,

        'pengingat' => true,

    ],

];