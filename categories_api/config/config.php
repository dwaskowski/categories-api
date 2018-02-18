<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'namespace' => 'categories_api',

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../../../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // DB configuration
        'db' => [
            'host' => '127.0.0.1',  //Your Database Hostname
            'port' => '3306',   //Database Port
            'username' => 'root',   //Database Username
            'password' => '',   //Database Password
            'name' => 'categories_api',  //Database name
            'charset' => 'utf8' //Database charset
        ],
    ],
];
