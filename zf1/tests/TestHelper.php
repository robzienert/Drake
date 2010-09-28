<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     UnitTests
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

error_reporting(E_ALL | E_STRICT);

/*
 * Determine the include path
 */
$library = realpath(dirname(__FILE__) . '/../library');
$tests   = dirname(__FILE__);

/*
 * Prepend the Zend Framework library/ and tests/ directories to the
 * include_path. This allows the tests to run out of the box and helps prevent
 * loading other copies of the framework code and tests that would supersede
 * this copy.
 */
$path = array(
    $library,
    $tests,
    get_include_path(),
);
set_include_path(implode(PATH_SEPARATOR, $path));

/*
 * Initialize the autoloader
 */
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();

$autoloader->registerNamespace('PHPUnit_');
$autoloader->registerNamespace('Drake_');

/*
 * Load the user-defined test configuration file, if it exists; otherwise, load
 * the default configuration.
 */
if (is_readable($tests . DIRECTORY_SEPARATOR . 'TestConfiguration.php')) {
    require_once $tests . DIRECTORY_SEPARATOR . 'TestConfiguration.php';
} else {
    require_once $tests . DIRECTORY_SEPARATOR . 'TestConfiguration.php.dist';
}

if (defined('TESTS_GENERATE_REPORT') && TESTS_GENERATE_REPORT === true &&
    version_compare(PHPUnit_Runner_Version::id(), '3.1.6', '>=')) {

    /*
     * Add Drake library/ directory to the PHPUnit code coverage
     * whitelist. This has the effect that only production code source files
     * appear in the code coverage report and that all production code source
     * files, even those that are not covered by a test yet, are processed.
     */
    PHPUnit_Util_Filter::addDirectoryToWhitelist($library);

    /*
     * Omit from code coverage reports the contents of the tests directory
     */
    foreach (array('.php', '.phtml', '.csv', '.inc') as $suffix) {
        PHPUnit_Util_Filter::addDirectoryToFilter($tests, $suffix);
    }
    PHPUnit_Util_Filter::addDirectoryToFilter(PEAR_INSTALL_DIR);
    PHPUnit_Util_Filter::addDirectoryToFilter(PHP_LIBDIR);
}


/*
 * Unset global variables that are no longer needed.
 */
unset($library, $tests, $path);