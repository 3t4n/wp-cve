<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

$styles[] = 'rectStyle';
class rectStyle extends style
{
	public $class = 'rectangle';
	public $name = 'rectangle';
	public $description = " Rectangle Buttons ";

	public $width = '75';


	public function mainCSS()
	{
		parent::mainCSS();
		//$this->addCSS('width', '75px');
	}
}
