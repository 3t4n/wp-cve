<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

class dropSquareStyle extends style
{

 	public $class = 'dropsquare single-meta';
	public $name  = 'dropsquare';
	public $description = " Square Buttons with dropSquare ";

	public $width = '60';  // in pix
	public $height = '65';  // in pix

	public $has_share = true;
	public $has_label = true;

	public function mainCSS()
	{
		parent::mainCSS();

		$this->addFlex();

		$meta_css = array(
				'position' => 'absolute',
				'left' => 0,
				'bottom' => '-3px',
				'width' => '100%',
				'background-color' => '#000',
				'line-height' => '20px',
				'transition' => 'bottom .2s linear',
				'z-index' =>  '-1',
				'display' => 'inline-block',
				'font-size' => '10px',
			);

		$meta_css_hover = array(
				'bottom' => '-20px',
		);

		$this->addCSS (
			$meta_css,
			'',
			'mb-label');

		$this->addCSS (
			$meta_css,
			'',
			'mb-share-count');

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

		$this->addCSS (
			array( 'z-index' => 0,
				 	'position' => 'relative',
				 ),
			'',
			'collection-item');

		//$this->addCSS('overflow', 'hidden','maxbutton-social');
	}


  public function prepareCSS($args)
  {
     parent::prepareCSS($args);

     if (! isset($args['child_override']) && $args['orientation'] == 'vertical')
     {
       $this->addCSS('margin-bottom', '20px', 'maxbutton-social', 'hover');
     }
  }

}


dropSquareStyle::registerStyle(60);
