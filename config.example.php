<?php

return [

    'batches' => [
        [
            'to' => ['+15551234567', '+15551234567', '+15551234567'],
            'srcFile' => 'call-scripts/test-script.xml'
        ],
        [
            'from' => '+15551234567',
            'to' => ['+15551234567', '+15551234567', '+15551234567'],
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
        ],

        'defaultFrom' => '+15551234567'

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