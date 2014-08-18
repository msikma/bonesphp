<?php

// Bones Framework - External Components Setup
// ------------------------------------------------------------------------
// Copyright (C) 2013-2014, Michiel Sikma <mike@letsdeliver.com>
// The use of this source code is governed by the MIT license.
// See the COPYRIGHT file for more information.

namespace BonesFw;

/* Load up the Twig template engine. Setup is done in the Controller class. */
if (file_exists(SYSTEM_PATH.'external/Twig/Autoloader.php')) {
    define('TWIG', true);
    require(SYSTEM_PATH.'external/Twig/Autoloader.php');
    \Twig_Autoloader::register();
}