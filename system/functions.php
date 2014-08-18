<?php

// Bones Framework - Framework Functions
// ------------------------------------------------------------------------
// Copyright (C) 2013-2014, Michiel Sikma <mike@letsdeliver.com>
// The use of this source code is governed by the MIT license.
// See the COPYRIGHT file for more information.

namespace BonesFw;

/* Turns a string into a slug usable for making links. */
function slugify($text, $repl='-')
{
    // Taken from Stack Overflow <http://stackoverflow.com/a/2955878/2582271>.
	$text = preg_replace('~[^\\pL\d]+~u', $repl, $text);
	$text = trim($text, $repl);
	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	$text = strtolower($text);
	$text = preg_replace('~[^-\w]+~', '', $text);
	
	return $text;
}

/* Two shortcuts to the internationalization class language methods. */
function l($key, $lang=null)
{
	return I18N::l($key, $lang);
}
function lf($key, $args=array(), $lang=null)
{
	return I18N::lf($key, $args, $lang);
}
