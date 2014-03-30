<?php

return [

    'callDetails' => [

        'number' => '8594667465',
        'message' => ['say' => 'This is a test call.'],
        'uploadUrl' => 'test-call.xml'

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