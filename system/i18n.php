<?php

// Bones Framework - Internationalization Class
// ------------------------------------------------------------------------
// Copyright (C) 2013-2014, Michiel Sikma <mike@letsdeliver.com>
// The use of this source code is governed by the MIT license.
// See the COPYRIGHT file for more information.

namespace BonesFw;

/* Note that Bones Framework makes use of IETF language tags as per RFC 4647,
 * except modified to use lowercase and underscores instead of dashes.
 * E.g. en_us for US English, de_de for German, etc. For more information,
 * see <https://tools.ietf.org/html/rfc4647>. */

class I18N
{
	private static $lang_strings = array();
	private static $curr_lang;
	
	/* Whether to return the key or an empty string upon lookup failure. */
	private static $return_key = true;
	
	/* List of previously loaded files. */
	private static $loaded_files = array();
	
	public static function load($name)
	{
		I18N::check_lang();
		$path = APP_PATH.'i18n/'.I18N::$curr_lang.'/'.$name.'.php';
		
		/* Check whether this file was previously loaded. */
		if (@I18N::$loaded_files[$path] === true) {
			return false;
		}
		/* Store the path so we don't accidentally include it twice. */
		I18N::$loaded_files[$path] = true;
		
		/* Include the file and then fish out the $lang array.
		 * We turn on buffering to avoid accidental printing of characters. */
		ob_start();
		include($path);
		ob_get_clean();
		
		if (isset($lang) && is_array($lang)) {
			I18N::$lang_strings[I18N::$curr_lang] = array_merge(
				I18N::$lang_strings[I18N::$curr_lang],
				$lang
			);
		}
		return true;
	}
	
	public static function get_lang()
	{
		if (!isset(I18N::$curr_lang)) {
			I18N::set_lang();
		}
		return I18N::$curr_lang;
	}
	
	public static function set_lang($lang=null)
	{
		if (!isset($lang)) {
			/* Since we don't have a set language, default to en_us
			 * if en_us language files exist, or else to the first directory,
			 * or to en_us if no language files exist at all. */
			$lang_path = APP_PATH.'i18n/';
			if (!is_dir($lang_path)) {
				/* No language directories at all. */
				$lang = 'en_us';
			}
			else {
				$lang_di = new \DirectoryIterator($lang_path);
				$has_en_us = false;
				foreach ($lang_di as $file) {
					if (strpos($file, '.') !== false) {
						continue;
					}
					$fn = strtolower(trim($file->getFilename()));
					if ($fn === 'en_us') {
						$has_en_us = true;
						break;
					}
				}
				if ($has_en_us) {
					$lang = 'en_us';
				}
				else {
					/* Default to whatever we found. */
					if (isset($fn)) {
						$lang = $fn;
					}
					else {
						$lang = 'en_us';
					}
				}
			}
			I18N::$curr_lang = $lang;
		}
		else {
			I18N::$curr_lang = $lang;
		}
		if (!isset(I18N::$lang_strings[I18N::$curr_lang])) {
			I18N::$lang_strings[I18N::$curr_lang] = array();
		}
	}
	
	private static function check_lang()
	{
		/* Ensures we've got any language set at all. */
		if (!isset(I18N::$curr_lang)) {
			I18N::set_lang();
		}
	}
	
	public static function l($key, $lang=null)
	{
		/* Returns a simple, unmodified language string. */
		I18N::check_lang();
		$str = @I18N::$lang_strings[I18N::$curr_lang][$key];
		return isset($str) ? $str : (I18N::$return_key ? $key : '');
	}
	
	public static function lf($key, $args=array(), $lang=null)
	{
		/* Returns a formatted language string. */
		$str = vsprintf(I18N::l($key, $lang), $args);
		return $str;
	}
}