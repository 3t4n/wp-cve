<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');


class squareStyle extends style
{
	public $class = 'square';
	public $name = 'square';
	public $description = " Square Social Buttons ";

	public $has_label = true;

	/* public function __construct()
	{
		parent::__construct();
	//	$this->css['mbsocial']['normal']['width'] = '400px';
} */

}

squareStyle::registerStyle('square');
