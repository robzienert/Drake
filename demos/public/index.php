<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../../library'),
    get_include_path(),
)));

/** Drake_Application */
require_once 'Drake/Application.php';

// Create application, bootstrap, and run
$application = new Drake_Application(
    APPLICATION_ENV,
    array(
        'configFile' => APPLICATION_PATH . '/configs/application.ini',
        'cacheOptions' => array(
            'enabled' => 'production' === APPLICATION_ENV,
        ),
    )
);
$application->bootstrap()
            ->run();