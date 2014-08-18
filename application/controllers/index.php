<?php

class IndexController extends BonesFw\Controller
{
	public $single_segment = false;
	
	public function __construct()
	{
		parent::__construct();
		BonesFw\I18N::load('index');
	}
	
	public function index($args=array(), $etc=array())
	{
		$data = array(
			'test_variable' => 'Bones Framework',
			'page_class' => array('page', 'index'),
		);
		if (!defined('TWIG')) {
		    /* The user hasn't installed Twig. Tell them where to get it. */
		    die('Bones Framework hasn\'t been completely installed yet. You still have to pull in the Twig submodule. See the website for details: <a href="https://github.com/msikma/bonesphp/">https://github.com/msikma/bonesphp/</a>');
		}
		$this->load_twig_view('pages/index', $data);
	}
}