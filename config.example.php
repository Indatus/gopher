<?php

return [

    'callDetails' => [

        'number' => '+15551234567',
        'destFile' => 'test-call.xml',
        'message' => [
            [
                'verb' => 'say',
                'text' => 'This is a test call.',
                'options' => ['voice' => 'woman']
            ],
            [
                'verb' => 'pause',
                'options' => ['length' => 10]
            ],
            [
                'verb' => 'say',
                'text' => 'Goodbye'
            ]
        ]

    ],

    'callService' => [

        'driver' => 'twilio',

        'credentials' => [
            'accountSid' => 'XXXXXXXXXXXXXXXXX',
            'authToken' => 'XXXXXXXXXXXXXXXXX',
            'fromPhone' => ''
        ]

    ],

    'fileStore' => [

        'driver' => 'S3',

        'credentials' => [
            'accessKey' => 'XXXXXXXXXXXXXXXXX',
            'secretKey' => 'XXXXXXXXXXXXXXXXX',
            'bucketName' => ''
        ]

    ]

];