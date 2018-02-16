<?php

use Propel\Runtime\ServiceContainer\StandardServiceContainer;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../config/config.php';

# Database Configuration
/** @var StandardServiceContainer $serviceContainer */
$serviceContainer = Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass($settings['settings']['namespace'], 'mysql');
$manager = new ConnectionManagerSingle();
$manager->setConfiguration([
    'dsn' => 'mysql:host=' . $settings['settings']['db']['host'] . ';port=' . $settings['settings']['db']['port'] . ';dbname=' . $settings['settings']['db']['name'],
    'user' => $settings['settings']['db']['username'],
    'password' => $settings['settings']['db']['password'],
    'settings' =>
        [
            'charset' => $settings['settings']['db']['charset'],
            'queries' => [],
        ],
    'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
]);
$manager->setName($settings['settings']['namespace']);
$serviceContainer->setConnectionManager($settings['settings']['namespace'], $manager);
$serviceContainer->setDefaultDatasource($settings['settings']['namespace']);

$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/Application/Dependencies.php';

// Register routes
require __DIR__ . '/../src/Application/Routes.php';

// Run app
$app->run();
