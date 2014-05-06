<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default FileSystem Driver
    |--------------------------------------------------------------------------
    |
    | Supported: "s3"
    |
    */

    'default' => 's3',

    /*
    |--------------------------------------------------------------------------
    | FileSystem Connections
    |--------------------------------------------------------------------------
    |
    */

    'connections' => [

        's3' => [
            'driver' => 's3',
            'key'    => 'AKIAJA53SRTONA7J7AMQ',
            'secret' => '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',
            'bucket' => 'com.indatus.callbot-bucket'
        ]

    ]

];
