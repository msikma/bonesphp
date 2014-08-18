<?php

// Bones Framework - Main Script (Cont'd.)
// ------------------------------------------------------------------------
// Copyright (C) 2013-2014, Michiel Sikma <mike@letsdeliver.com>
// The use of this source code is governed by the MIT license.
// See the COPYRIGHT file for more information.

namespace BonesFw;

/* Resolve the required Bones Framework paths. */
$paths = array(
	'SYSTEM_PATH' => SYSTEM_DIR,
	'APP_PATH' => APP_DIR,
	'STATIC_PATH' => STATIC_DIR,
	'CACHE_PATH' => SYSTEM_DIR.'/cache'
);
define('BASE_PATH', rtrim(dirname($index_file), '/').'/');
foreach ($paths as $name => $dir) {
	$path = realpath(BASE_PATH.$dir);
	if (!is_dir($path)) {
		die('The '.str_replace('PATH', 'DIR', $name).' has not been set up correctly; please check the framework\'s index.php file.');
	}
	$path = rtrim($path, '/').'/';
	define($name, $path);
}
/* Set a default timezone to avoid errors. */
date_default_timezone_set('UTC');

/* Load up all the framework's components. */
require(SYSTEM_PATH.'system.php');
require(SYSTEM_PATH.'functions.php');
require(SYSTEM_PATH.'library.php');
require(SYSTEM_PATH.'i18n.php');
require(SYSTEM_PATH.'router.php');
require(SYSTEM_PATH.'model.php');
require(SYSTEM_PATH.'controller.php');

/* Load external components. */
require(SYSTEM_PATH.'externals.php');

System::initialize();
Router::parse_request();
Router::route_request();
