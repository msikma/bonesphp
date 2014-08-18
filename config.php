<?php

// Bones Framework - Configuration file
// ------------------------------------------------------------------------
// Copyright (C) 2013-2014, Michiel Sikma <mike@letsdeliver.com>
// The use of this source code is governed by the MIT license.
// See the COPYRIGHT file for more information.

// Either DEV or LIVE; this turns error reporting on or off.
define('APP_STATUS',				  'DEV');

// Base URL of your application with trailing slash.
// e.g. http://www.domain.com/my-blog/
$settings['base_url']				= 'http://localhost/bonesphp/';
// URL containing static resources, e.g. CSS, JS and layout image files.
// Should have a trailing slash too.
$settings['static_url']				= $settings['base_url'].'static/';

// MySQL database settings. Only the mysqli driver is supported as of now.
$settings['db_name']				= 'bonesphp';
$settings['db_user']				= 'root';
$settings['db_pass']				= 'root';
$settings['db_host']				= 'localhost';
$settings['db_driver']				= 'mysqli';

// Default controller, loaded on requesting the root URL.
$settings['app_default_controller']	= 'index';

// Whether to cache Twig template files (set to false for development).
$settings['twig_cache']				= false;
