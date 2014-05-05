<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default FileSystem Driver
    |--------------------------------------------------------------------------
    |
    | Supported: "s3", "rackspace", "dropbox", "ftp", "sftp"
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
            'key'    => 'your-public-key',
            'secret' => 'your-secret-key',
            'bucket' => 'your-bucket-name'
        ],

        'rackspace' => [
            'driver'    => 'rackspace',
            'username'  => 'your-username',
            'key'       => 'your-publick-key',
            'region'    => 'your-region',
            'container' => 'your-container-name'
        ],

        'dropbox' => [
            'driver' => 'dropbox',
            'token'  => 'your-token',
            'app'    => 'your-app-name'
        ],

        'ftp' => [
            'driver'     => 'ftp',
            'host'       => 'your-hostname',
            'username'   => 'your-username',
            'password'   => 'your-password',

            /** OPTIONAL SETTINGS **/

            // 'port'    => 21,
            // 'root'    => '/path/to/root',
            // 'passive' => true,
            // 'ssl'     => true,
            // 'timeout' => 30
        ],

        'sftp' => [
            'driver'     => 'sftp',
            'host'       => 'your-hostname',
            'username'   => 'your-username',
            'password'   => 'your-password',
            'port'       => 21,
            'privateKey' => 'path/to/private-key',
            'root'       => '/path/to/root',
            'timeout'    => 30
        ]

    ]

];
