<?php

return [

    'batches' => [
        [
            'from' => '+15551234567',
            'to' => ['+15551234567', '+15551234567', '+15551234567'],
            'callbackUrl' => 'http://www.example.com/call.xml',
            'srcFile' => 'call-scripts/test-script.xml'
        ],
        [
            'from' => '+15551234567',
            'to' => ['+15551234567', '+15551234567', '+15551234567'],
            'callbackUrl' => 'http://www.example.com/call-2.xml',
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
        ]

    ],

    'callService' => [

        'driver' => 'twilio',

        'credentials' => [
            'accountSid' => 'XXXXXXXXXXXXXXXXX',
            'authToken' => 'XXXXXXXXXXXXXXXXX'
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