<?php

// Bones Framework - Controller Class
// ------------------------------------------------------------------------
// Copyright (C) 2013-2014, Michiel Sikma <mike@letsdeliver.com>
// The use of this source code is governed by the MIT license.
// See the COPYRIGHT file for more information.

namespace BonesFw;

/* todo: destroy the camelcase */
class Controller
{
	public $single_segment;
	
	private $twig_ldr;
	private $twig_env;
	private $twig_cache;
	private $twig_tpl_dir;
	
	private $default_vars;
	
	private $headers_done = false;
	private $mime_default = 'text/html';
	private $charset_default = 'utf-8';
	
	public function __construct()
	{
		/* Set the Twig operation directories. */
		$this->twig_cache_dir = CACHE_PATH.'twig/';
		$this->twig_use_cache = System::get_setting('twig_cache');
		$this->twig_tpl_dir = APP_PATH.'views/';
		
		/* Set up default variables that all templates can use. */
		$this->default_vars = array(
			'base_url' => System::get_setting('base_url'),
			'static_url' => System::get_setting('static_url'),
		);
	}
	
	public function set_default_mime($mime)
	{
		$this->mime_default = $mime;
	}
	
	public function set_default_charset($charset)
	{
		$this->charset_default = $charset;
	}
	
	public function load_view($name, $data=array(), $mime='default')
	{
		$mime = $mime == 'default' ? $this->mime_default : $mime;
		$this->output($this->_get_view($name, $data, false), $mime);
	}
	
	public function load_twig_view($name, $data=array(), $mime='default')
	{
		$mime = $mime == 'default' ? $this->mime_default : $mime;
		$this->output($this->_get_view($name, $data, true), $mime);
	}
	
	public function return_view($name, $data=array())
	{
		return $this->_get_view($name, $data, false);
	}
	
	public function return_twig_view($name, $data=array())
	{
		return $this->_get_view($name, $data, true);
	}
	
	private function _get_view($name, $data, $twig=false)
	{
		$view_name = $name.'.php';
		$view_path = APP_PATH.'views/'.$view_name;
		if (!is_file($view_path)) {
			return die("<strong>Data Framework</strong> Fatal Error - Can't load '{$name}' view: '{$view_path}' does not exist.");
		}
		$buffer = '';
		
		if ($twig == true) {
			/* Call up Twig to render a view. */
			if (!isset($this->twig_ldr)) {
				/* Twig has not been initialized yet. Do so now. */
				$this->_init_twig();
			}
			/* Add standard variables to $data before rendering the view. */
			$data = array_merge($this->default_vars, $data);
			$buffer = $this->twig_env->render($view_name, $data);
		}
		else {
			/* If not using Twig, simply include the view file. */
			$buffer = $this->_include_view(APP_PATH.'views/'.$name.'.php', $data);
		}
		return $buffer;
	}
	
	/* Sends a string to the client. */
	public function output($str, $mime=null, $charset='default')
	{
		if ($this->headers_done == false) {
			$charset = $charset == 'default' ? $this->charset_default : $charset;
			$header_str = sprintf('Content-Type:%s charset=%s',
				isset($mime) ? ' '.$mime.';' : '',
				$charset
			);
			header($header_str);
			$this->headers_done = true;
		}
		print($str);
	}
	
	private function _init_twig()
	{
		/* Initialize Twig and store the instance locally. */
		if (!defined('TWIG')) {
		    /* If we haven't initialized Twig, error out. */
		    die('<strong>Bones Framework</strong> Fatal Error - Twig is not initialized.');
		}
		$this->twig_ldr = new \Twig_Loader_Filesystem($this->twig_tpl_dir);
		$this->twig_env = new \Twig_Environment($this->twig_ldr, array(
			'cache' => $this->twig_use_cache ? $this->twig_cache_dir : false,
		));
		/* Include the views directory as a base template path. */
		$this->twig_ldr->addPath(APP_PATH.'views/');
		
		/* Initialize our own helper functions. */
		$l_func = new \Twig_SimpleFunction('l', function($str, $lang=null)
		{
			return I18N::l($str, $lang);
		});
		$lf_func = new \Twig_SimpleFunction('lf', function($str, $args=array(), $lang=null)
		{
			return I18N::lf($str, $args, $lang);
		});
		$strftime_func = new \Twig_SimpleFilter('strftime', function($timestamp, $format="%c") {
			return strftime($format, $timestamp);
		});
		$this->twig_env->addFunction($l_func);
		$this->twig_env->addFunction($lf_func);
		$this->twig_env->addFilter($strftime_func);
	}
	
	private function _include_view($__bfw_path, $__bfw_data)
	{
		/* Include the view and return the result. */
		ob_start();
		/* Avoid an accidental or intentional collision. */
		unset($__bfw_data['__bfw_path']);
		foreach ($__bfw_data as $k => $v) {
			$$k = $v;
		}
		include($__bfw_path);
		return ob_get_clean();
	}
	
	public function is_single_segment()
	{
		$sseg = @$this->single_segment === true;
		return $sseg;
	}
	
	public function load_library($name)
	{
		/* See if we can find the library code. */
		$lib_path = APP_PATH.'libraries/'.$name.'.php';
		if (!is_file($lib_path)) {
			die('<strong>Bones Framework</strong> Fatal Error - Can\'t load \''.$name.'\' library: \''.$lib_path.'\' does not exist.');
		}
		include($lib_path);
		$classname = ucwords($name).'Library';
		if (!class_exists($classname)) {
			die('<strong>Bones Framework</strong> Fatal Error - Can\'t load \''.$name.'\' library: \''.$classname.'\' is not a valid class.');
		}
		$inst = new $classname;
		return $inst;
	}
}
