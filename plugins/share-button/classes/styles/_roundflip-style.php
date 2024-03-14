<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

roundFlipStyle::registerStyle();

class roundFlipStyle extends roundStyle
{

	public $class = 'roundflip single-meta';
	public $name = 'roundflip';
	public $description = " Round Buttons with Flip Hover Effect ";


	public function mainCSS()
	{
		parent::mainCSS();

		$css = array(
				'transition' => 'all .2s linear',
				'width' => '100%',
				'height' => '100%',
				'position' => 'absolute',
				'transform' => 'translateY(100%)',
				'display' => 'flex',
		);

		$css_hover = array(
				'bottom' => '0',

				);


		$this->addCSS($css, '', 'mb-label');
		$this->addCSS($css, '', 'mb-share-count');

		$this->addCSS('bottom', '0', 'mb-label', 'hover');
	 	$this->addCSS('bottom', '0', 'mb-share-count','hover');

		$this->addCSS('transform', 'translateY(0)', 'mb-label', 'hover');
	 	$this->addCSS('transform', 'translateY(0)', 'mb-share-count','hover');

	 	$this->addCSS('transform', 'translateY(-100%)', 'mb-icon-wrapper', 'hover');
	 	$this->addCSS('display', 'flex', 'mb-icon-wrapper', 'hover');
	 	$this->addCSS('transition', 'all .2s ease-in-out', 'mb-icon-wrapper');

	 	$this->addCSS('transform', 'none', 'maxbutton-social', 'hover');
	 	$this->addCSS('overflow', 'hidden', 'maxbutton-social');


	}

}
