<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

class shiftSquareStyle extends DropSquarestyle
{
	public $class = 'shiftsquare';
	public $name = 'shiftsquare';
	public $description = " Square Buttons with Shift Hover Effect ";

	public function mainCSS()
	{
		parent::mainCSS();

		$this->addCSS('overflow', 'hidden', 'collection-item');


		$this->addCSS( array(
				'position' => 'absolute',
				'bottom' => '0',
				'height' => '20px',
				'z-index' => '1',
				'transition' => 'all .2s ease-in-out',
				'background' => 'transparent',
			),
			'',
			'mb-share-count');

		$this->addCSS ( array(
				'transform' => 'translateX(60px)',
				'bottom' => '0',
				'background' => 'transparent',
			),
			'',
			'mb-share-count',
			'hover'
		);

		$this->addCSS ( array (
				'transform' => 'translateX(-60px)',
				'position' => 'absolute',
				'bottom' => 0,
				'transition' => 'all .2s ease-in-out',
				'background-color' => 'transparent',
				),
				'',
				'mb-label'
		);

		$this->addCSS ( array (
				'transform' => 'translateX(0px)',
				'z-index' => '1',
				'bottom' => '0',
				'background-color' => 'transparent',
				),
				'',
				'mb-label',
				'hover'
		);

		$this->addCSS ('transform', 'translateY(-8px)', 'mb-icon', 'hover');
		$this->addCSS ('transition', 'all .2s ease-in-out', 'mb-icon');

	}

	public function prepareCSS($args)
	{
		$args['child_override']  = true;
		parent::prepareCSS($args);


		// prevent dropsquare from doing it's thing here.
	}


}

shiftSquareStyle::registerStyle(70);
