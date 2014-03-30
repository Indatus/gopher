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
            'accountSid' => TW_ACCOUNT_SID,
            'authToken' => TW_AUTH_TOKEN,
            'sourcePhone' => TW_SOURCE_PHONE
        ]

    ],

    'fileStore' => [

        'driver' => 'S3',

        'credentials' => [
            'accessKey' => AWS_ACCESS_KEY,
            'secretKey' => AWS_SECRET_KEY
        ]

    ]

];