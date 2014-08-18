<?php

// Bones Framework - URI Router Class
// ------------------------------------------------------------------------
// Copyright (C) 2013-2014, Michiel Sikma <mike@letsdeliver.com>
// The use of this source code is governed by the MIT license.
// See the COPYRIGHT file for more information.

namespace BonesFw;

class Router
{
	private static $route_controller;
	private static $route_segments;
	
	public static function parse_request()
	{
		$uri_string = Router::_get_uri_string();
		if (!isset($uri_string)) {
			die('Unable to parse the request. Ensure that the PATH_INFO, REQUEST_URI or QUERY_STRING are available in the $_SERVER variable.');
		}
		
		/* Cut up the request string into a clean array. */
		$uri_segments = array_map('trim', explode('/', trim($uri_string, '/')));
		
		/* The first segment is the controller. The rest is either
		 * all arguments, or a method name followed by arguments. */
		$controller = array_slice($uri_segments, 0, 1);
		$controller = reset($controller);
		$segments = array_slice($uri_segments, 1);
		
		Router::$route_controller = $controller;
		Router::$route_segments = $segments;
	}
	
	public static function route_request()
	{
		/* Get the name of the default router. */
		$default = System::get_setting('app_default_controller');
		
		/* Get the information we extracted from the user's request. */
		$controller = Router::$route_controller;
		$segments = Router::$route_segments;
		
		if ($controller == '') {
			/* If the controller is an empty string, the user is requesting
			 * the home page. So we'll load the default controller. */
			$controller = $default;
		}
		Router::_load_route($controller, $segments);
	}
	
	public static function controller_slugify($ctrl)
	{
		return str_replace(' ', '', ucwords(str_replace('-', ' ', slugify($ctrl))));
	}
	
	private static function _load_route($controller, $segments=array(), $etc=array())
	{
		/* In case we're loading an error handler, we should look in
		 * the application's 'errors' directory. */
		$route_dir = @$etc['error'] == true ? 'errors' : 'controllers';
		
		/* The path to the controller file. */
		$route_path = APP_PATH.$route_dir.'/'.$controller.'.php';
		
		if (!is_file($route_path)) {
			/* Looks like the controller file doesn't exist. */
			if (!isset($etc['error'])
			||  $etc['error'] != true) {
				$etc['error'] = true;
				$etc['original_controller'] = $controller;
				return Router::_load_route('error404', $segments, $etc);
			}
			else {
				/* No controller to load, and no error handler. */
				die('<strong>Bones Framework</strong> - Fatal Error: couldn\'t load '.$controller.' error handler.<br />$segments: '.print_r($segments, true).'<br />$etc: '.print_r($etc, true));
			}
		}
		/* Now load up the controller to see what we're dealing with. */
		include($route_path);
		
		$classname = Router::controller_slugify($controller).'Controller';
		
		/* Check if the expected class name exists;
		 * if not, the class is malformed. */
		if (!class_exists($classname)) {
			/* Since the file exists but the class doesn't, the programmer
			 * did something wrong. So a fatal error is OK here. */
			die('<strong>Bones Framework</strong> - Fatal Error: the '.$controller.' controller doesn\'t have a (valid) '.$classname.' class.<br />$segments: '.print_r($segments, true).'<br />$etc: '.print_r($etc, true));
		}
		
		/* Initiate the controller. */
		$ctrl = new $classname;
		$target = Router::controller_slugify($controller);
		if ($ctrl->is_single_segment()) {
			/* If this is a single segment controller, load up
			 * the main method with all arguments. */
			$method = $target;
			$args = $segments;
		}
		else {
			/* Use the first argument as the method name. */
			$method = array_slice($segments, 0, 1);
			$method = reset($method);
			if ($method == false) {
				/* If there are no extra arguments, use the main method. */
				$method = $target;
			}
			$args = array_slice($segments, 1);
		}
		
		/* Check if the desired method exists. */
		if (method_exists($ctrl, $method)) {
			/* Since we have a valid controller and method now,
			 * we can simply run the controller code now. */
			$ctrl->$method($args, $etc);
		}
		else {
			$etc['original_method'] = $method;
			$method = '__etc';
			if (method_exists($ctrl, $method)) {
				/* Run the controller's catch-all method. */
				$ctrl->$method($args, $etc);
			}
			else {
				/* Couldn't find a catch-all method. 404 redirect. */
				$etc['error'] = true;
				return Router::_load_route('error404', $segments, $etc);
			}
		}
	}
	
	public static function _get_uri_string()
	{
		/* To account for different server setups,
		 * we'll try several options one at a time. */
		if (isset($_SERVER['PATH_INFO'])) {
			return $_SERVER['PATH_INFO'];
		}
		
		if (($uri = Router::_parse_request_uri()) !== false) {
			return $uri;
		}
		/* todo: debug query string */
		if (($uri = Router::_parse_query_string()) !== false) {
			return $uri;
		}
	}
	
	public static function _parse_request_uri()
	{
		/* todo: document */
		$request_uri = @$_SERVER['REQUEST_URI'];
		$script_name = @$_SERVER['SCRIPT_NAME'];
		
		if (!isset($request_uri, $script_name)) {
			return false;
		}
		
		$uri = parse_url($request_uri);
		$query = isset($uri['query']) ? $uri['query'] : '';
		$uri = isset($uri['path']) ? rawurldecode($uri['path']) : '';
		
		foreach (array($script_name, dirname($script_name)) as $script_chk) {
			if (strpos($uri, $script_chk) === 0) {
				$uri = (string)substr($uri, strlen($script_chk));
			}
		}
		
		if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0) {
			$query = explode('?', $query, 2);
			$uri = rawurldecode($query[0]);
			$_SERVER['QUERY_STRING'] = isset($query[1]) ? $query[1] : '';
		}
		else {
			$_SERVER['QUERY_STRING'] = $query;
		}
		
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		
		if ($uri === '') {
			$uri = '/';
		}
		
		return $uri;
	}
	
	public static function _parse_query_string()
	{
		/* todo: document. and test, maybe. */
		$uri = $_SERVER['QUERY_STRING'];
		
		if (trim($uri, '/') === '') {
			return false;
		}
		else
		if (strncmp($uri, '/', 1) === 0) {
			$uri = explode('?', $uri, 2);
			$_SERVER['QUERY_STRING'] = isset($uri[1]) ? $uri[1] : '';
			$uri = rawurldecode($uri[0]);
		}
		
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		
		return $uri;
	}
}