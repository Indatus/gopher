<?php

return [

    'callDetails' => [

        'number' => '8594667465',
        'srcFile' => null,
        'destFile' => 'test-call.xml',
        'message' => ['say' => 'This is a test call.']

    ],

    'callService' => [

        'driver' => 'twilio',

        'credentials' => [
            'accountSid' => 'XXXXXXXXXXXXXXXXX',
            'authToken' => 'XXXXXXXXXXXXXXXXX',
            'fromPhone' => '+15551234567'
        ]

    ],

    'fileStore' => [

        'driver' => 'S3',

        'credentials' => [
            'accessKey' => 'XXXXXXXXXXXXXXXXX',
            'secretKey' => 'XXXXXXXXXXXXXXXXX'
        ]

    ]

];