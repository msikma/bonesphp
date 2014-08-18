<?php

class Error404Controller extends BonesFw\Controller
{
	public $single_segment = true;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function error404($args=array(), $etc=array())
	{
		var_dump('error404 controller!');
		var_dump($args);
		var_dump($etc);
	}
}