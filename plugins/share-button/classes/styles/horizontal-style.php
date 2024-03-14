<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

class horizontalStyle extends style
{
	public $class = 'stretch';
	public $name = 'stretch';
	public $description = " Stretch Style ";

	public $has_label = true;

	public $display = array('normal' => 'icon,count',
													'hover'  => 'icon,count,label',
	);

	public function __construct()
	{
			parent::__construct();


//			$this->css['maxsocial']['flex'] = '1.2';
			$this->addCSS('display', 'flex', 'maxsocial');

			$this->addCSS('flex', '1.2', 'mb-item', 'hover');

			$itemcss =  array('flex' => 1,
			  			'float' => 'left',
			  			'height' => '100%',
			  			'-webkit-transition' => 'all .1s linear',
			  			'transition' =>  'all .1s linear',
						);
			$this->addCSS($itemcss, '','mb-item');

			$this->addCSS ( array (
				'display' => 'block',
				'display' => '-webkit-box',
				'display' => '-webkit-flex',
				'display' => '-moz-box',
				'display' => '-ms-flexbox',
				'display' => 'flex',
				'text-transform' => 'none',
				'-webkit-flex-flow' => 'row wrap',
				'-ms-flex-flow' => 'row wrap',
				'flex-flow' => 'row wrap',
				'width' => '100%',
				),
				'',
				'mb-social');

			$this->addCSS('flex', '1.2', 'maxbutton-social', 'hover');

			// the label
			$this->addCSS ( array (
				'text-align' => 'center',
				'flex-grow' => 3,
				'margin' => 'auto',
				'display' => 'none',
			'opacity' => 0,
				'-webkit-transition' => 'all .4s linear',
				'transition' => 'all .4s linear',
				),
				'',
				'mb-label'
			);

			// label hover
			$this->addCSS( array(
				'display' => 'inline-block',
				'opacity' => '1',

				),
				'',
				'mb-label',
				'hover'

			);

			$this->addCSS ( array (
				'text-align' => 'center',
				'flex-grow' => 2,
				'margin' => 'auto',
				),
				'',
				'mb-icon-wrapper'
			);

			$this->addCSS ( array (
				'font-size' => '16px',
				'margin' => 'auto 0',
				//'flex-grow' => '1',
				'display' => 'inline-block',
				'transform' => 'translateX(-10px)',
				),
				'',
				'mb-share-count'
			);

			$this->addCSS('flex-grow', '1', 'mb-share-count', 'hover');

	}

	public function mainCSS()
	{

		parent::mainCSS();
		//$this->addFlex('maxcollection');

		// flex collection
	/*	$this->addCSS ( array (
			'flex' => '1.2'
			),
			'',
			'collection-item',
			'hover'
		);
*/

		// flex item
		/*$this->addCSS ( array (
  			'flex' => 1,
  			'float' => 'left',
  			'height' => '100%',
  			'-webkit-transition' => 'all .1s linear',
  			'transition' =>  'all .1s linear',
  			),
  			'',
  			'collection-item'
  			);
*/


//  		$this->addCSS('transform','translateX(-10px)','mb-share-count', 'hover');


	} // construct

	public function prepareCSS($args)
	{
			parent::prepareCSS($args);

			$default_args = array(
				'orientation' => 'horizontal',
				'is_static' => false,
			);

			$args = wp_parse_args($args, $default_args);

			if ($args['is_static'] == 1)
			{
					$this->addCSS(array(
							'width' => '95%', // not 100% due to margins
							'height' => 'auto',

						),
						'',
						'mb-social'
					);


			}

	}

	public function preParse($css)
	{
		$css = parent::preParse($css);

		$css['mb-social']['normal']['width']  = '100%';


		return $css;
	}

} // class

horizontalStyle::registerStyle('stretch');
