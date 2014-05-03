<?php

return [

    // Call Service settings
    'callService' => [

        'driver' => 'twilio',

        'credentials' => [
            'accountSid' => 'XXXXXXXXXXXXXXXXX',
            'authToken' => 'XXXXXXXXXXXXXXXXX'
        ],

        'defaultFrom' => '5551234567'

    ],

    // FileSystem settings
    'fileSystem' => [

        'driver' => 'S3',

        'credentials' => [
            'accessKey' => 'XXXXXXXXXXXXXXXXX',
            'secretKey' => 'XXXXXXXXXXXXXXXXX'
        ],

        'uploadDir' => 'https://s3.amazonaws.com/BUCKET_NAME'

    ],

    // Default timezone
    'timezone' => 'America/New_York',

    // Configured batches of calls
    'batches' => [
        'example-1' =>
            [
                'to' => ['5551234567', '5551234567', '5551234567'],
                'script' => 'call-scripts/test-script.xml'
            ]
    ]
];