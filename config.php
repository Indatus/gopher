<?php

return [

    'callDetails' => [

        'number' => '8594667465',
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