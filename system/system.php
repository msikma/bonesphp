<?php

// Bones Framework - System Class
// ------------------------------------------------------------------------
// Copyright (C) 2013-2014, Michiel Sikma <mike@letsdeliver.com>
// The use of this source code is governed by the MIT license.
// See the COPYRIGHT file for more information.

namespace BonesFw;

class System
{
	private static $bfw_version = '1.0';
	private static $settings;
	
	public static function initialize()
	{
		/* Pick up the settings array, save it in this class
		 * and then unset the original. */
		System::setup($GLOBALS['settings']);
	}
	
	private static function setup($settings)
	{
		/* Unset the global copy. */
		unset($GLOBALS['settings']);
		System::$settings = $settings;
		/* Set up the BASE_URL constant. */
		$base = rtrim($settings['base_url'], '/').'/';
		define('BASE_URL', $base);
	}
	
	public static function get_setting($key)
	{
		$val = @System::$settings[$key];
		return isset($val) ? $val : null;
	}
}

/* Note: both are defined in the main namespace. */
function base_url()
{
	/* Returns the base URL. Should be used only for locating controllers. */
	return System::get_setting('base_url');
}

/* Note: defined in the primary namespace. */
function static_url()
{
	/* Returns the static URL.
	 * Used to refer to the static resources located there. */
	return System::get_setting('static_url');
}
