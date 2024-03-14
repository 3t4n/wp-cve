<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

class roundStyle extends style
{
	public $class = 'round';
	public $name = 'round';
	public $description = ' Round Buttons Basic ';

	public $has_label = true;


  public function __construct()
	{
			parent::__construct();

			$this->css['mb-social']['normal']['border-radius'] = '50% 50%';
	}


} // class

roundStyle::registerStyle('round');
