<?php

return [

    'batches' => [
        [
            'to' => ['5551234567', '5551234567', '5551234567'],
            'script' => 'call-scripts/test-script.xml'
        ]
    ],

    'callService' => [

        'driver' => 'twilio',

        'credentials' => [
            'accountSid' => 'XXXXXXXXXXXXXXXXX',
            'authToken' => 'XXXXXXXXXXXXXXXXX'
        ],

        'defaultFrom' => '5551234567'

    ],

    'fileStore' => [

        'driver' => 'S3',

        'credentials' => [
            'accessKey' => 'XXXXXXXXXXXXXXXXX',
            'secretKey' => 'XXXXXXXXXXXXXXXXX'
        ],

        'uploadDir' => 'https://s3.amazonaws.com/BUCKET_NAME'

    ],

    'timezone' => 'America/New_York'

];