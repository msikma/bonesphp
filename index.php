<?php

// Bones Framework - Main Script
// ------------------------------------------------------------------------
// Copyright (C) 2013-2014, Michiel Sikma <mike@letsdeliver.com>
// The use of this source code is governed by the MIT license.
// See the COPYRIGHT file for more information.

// All URLs are relative to this file.
define('CONFIG',		'config.php');		// Configuration file
define('SYSTEM_DIR',	'system');			// System directory
define('APP_DIR',		'application');		// Application directory
define('STATIC_DIR',	'static');			// Static files directory

require(CONFIG);
$index_file = __FILE__;

/* Kickstart the system. */
require(SYSTEM_DIR.'/main.php');
