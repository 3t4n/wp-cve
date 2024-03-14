<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use \MaxButtons\maxField as maxField;
use \MaxButtons\maxBlocks as maxBlocks;

class liftSquareStyle extends dropSquarestyle
{
	public $class = 'liftsquare single-meta';
	public $name = 'liftsquare';
	public $description = ' Square style with lifting hover ';

	public function mainCSS()
	{
		parent::mainCSS();

		$this->addCSS('overflow', 'hidden', 'collection-item');
		$this->addCSS('transform', 'translateY(-10px)', 'mb-icon', 'hover');
		$this->addCSS('transition', 'all .2s linear', 'mb-icon');

		$meta_css = array(
			'bottom' => '-20px',
			'z-index' => 'auto',
			'background' => 'transparent',

		);

		$this->addCSS (
			$meta_css,
			'',
			'mb-label');

		$this->addCSS (
			$meta_css,
			'',
			'mb-share-count');

		$meta_css_hover = array(
				'bottom' => '0px',
				'background' => 'transparent',
		);

		$this->addCSS(
			$meta_css_hover,
			'',
			'mb-label',
			'hover'
		);

		$this->addCSS(
			$meta_css_hover,
			'',
			'mb-share-count',
			'hover'
		);

	}

	public function setActive()
	{
		parent::setActive();
	}

	public function prepareCSS($args)
	{
		$args['child_override']  = true;
		parent::prepareCSS($args);


		// prevent dropsquare from doing it's thing here.
	}

}

liftSquareStyle::registerStyle(65);
