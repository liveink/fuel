#!/usr/bin/php -q
<?php

/**
 * This is the path to the app directory.
 */
$app_path = 'fuel/app';

/**
 * This is the path to the package directory.
 */
$package_path = 'fuel/packages';

/**
 * This is the path to the core directory.
 */
$core_path = 'fuel/core';


use Fuel\Application as App;

// This is purely for ease of use, creating an alias of DS
define('DS', DIRECTORY_SEPARATOR);

define('CRLF', sprintf('%s%s', chr(13), chr(10)));

/**
 * Do we have access to mbstring?
 * We need this in order to work with UTF-8 strings
 */
define('MBSTRING', function_exists('mb_get_info'));

// Determine the app path
if ( ! is_dir($app_path) and is_dir(DOCROOT.$app_path))
{
	$app_path = DOCROOT.$app_path;
}

// Determine the package path
if ( ! is_dir($package_path) and is_dir(DOCROOT.$package_path))
{
	$package_path = DOCROOT.$package_path;
}

// Define the global path constants
define('APPPATH', realpath($app_path).DS);
define('PKGPATH', realpath($package_path).DS);
define('COREPATH', realpath($core_path).DS);

// save a bit of memory by unsetting the path array
unset($app_path, $package_path);

define('APP_NAMESPACE', trim($app_namespace, '\\'));

// Load in the core functions that are available app wide
require COREPATH.'base.php';

/**
 * Load in the autoloader class then register any app and core autoloaders.
 */
require COREPATH.'classes'.DS.'autoloader.php';

$autoloaders['core'] = require COREPATH.'autoload.php';

$loader = new Autoloader;

$loader->add_namespaces(array(
	'Fuel\\Application'		=> APPPATH.'/classes/',
	'Cli'					=> PKGPATH.'/cli/classes/',
));

$loader->register();

$autoloaders['app'] = $loader;

// Load in the core class
require COREPATH.'classes'.DS.'fuel.php';

// Initialize the framework
// and start buffering the output.
App\Fuel::init();

Cli\Cli::init($_SERVER['argv']);

echo PHP_EOL;

/* End of file fuel.php */