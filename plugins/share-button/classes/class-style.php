<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use \MaxButtons\maxBlocks as maxBlocks;
use \MaxButtons\maxUtils as maxUtils;

class style
{
	protected $collection;

	public $class;
	public $name;
	public $description = ' Lorum Ipsum Stylum ';

 // Defines if style supports label and share counts
	public $has_share = false;
	public $has_label = false;

	/*
	Controls what to display for this style and when
	Usage
		Pseudo (normal/hover) -> [item] , array('item', 'item'), 'item,item'
														^ one item,  ^ or 								^ and, show both
	*/
	public $display = array('normal' => 'icon',
													'hover'  => array('count', 'label'),
	);

	protected $display_active = array();
	protected $effect_active = false;

	// possible ITEM css classes
	protected $items = array('count' => 'mb-share-count',
													 'icon' => 'mb-icon-wrapper',
													 'label' =>  'mb-label',
												 );

 	public $css = array(
			'maxsocial' => array( // the container
													'normal' =>
												  array('display' => 'inline-block',
															  'clear' => 'both',
																'z-index' => 9999,
																'position' => 'relative',
																'width' => '100%',
																'max-width' => 'none',
																'line-height' => '1.1',
																'box-sizing' => 'border-box',
												  ),
						//				  		'hover' => '',
											),

				'mb-item' => array('normal' =>
												    array ( 'display' => 'flex',
	 																	 'justify-content' => 'center',
                                     'align-items' => 'center',
																		 'box-sizing' => 'border-box',
																	 	 'position' => 'relative',
																		 'line-height' => 'inherit',
																		 'text-align' => 'center',
 													 ),
												),
				'mb-social' => array('normal' => // the button
												   array ( 'display' => 'flex',
																	 'justify-content' => 'center',
																	 'align-items' => 'center',
																	 'text-decoration' => 'none',
																	 'position' => 'relative',
																	 'border' => 0,
																	 'box-shadow' => 'none',
																	 'box-shadow-width' => 0,
																	 'box-shadow-offset-left' => 0,
																	 'box-shadow-offset-top' => 0,

													 ),
	//												 'hover' => array(),
											),
//					'mb-share-count' => array();
					//'hover' => array('normal' => array( 'display' => 'none')),
					//'normal' => array('hover' => array( 'display' => 'none')),

	);


	public static function registerStyle($name)
	{
		$class = get_called_class();
		styles::registerStyle($class, $name);

	}

	public function __construct()
	{

	}

	public function getCSS()
	{
		return $this->css;

	}

	/** Add effects to the styling . addCSS uses the last arg to bounce back CSS, since it's called by block
	* @param $effect Name of the effect
	* @param $args  Arguments, some effects might need specific args.
	*/
	public function addEffect($css, $effect, $args = array())
	{
		$this->effect_active = $effect;

		switch($effect)
		{
			case 'drop':

				$line_height = intval($args['line_height']) * 2;

				$normcss = array(
					'position' => 'absolute',
					'left' => 0,
					'bottom' => '-3px',
					'width' => '100%',
					'transition' => 'bottom .2s linear',
					'z-index' =>  '-1',
					'line-height' => $line_height .'px',
			//		'display' => 'inline-block',
				  'background-color' => '#000',

				);

				$css = $this->addCSS($normcss, false, 'mb-label', 'normal', $css);
				$css = $this->addCSS($normcss, false, 'mb-share-count', 'normal', $css);

				$hovercss = array(
					'bottom' =>  '-' . $line_height . 'px',
				);

				$css = $this->addCSS($hovercss, false, 'mb-label', 'hover', $css);
				$css = $this->addCSS($hovercss, false, 'mb-share-count','hover', $css);

				$css = $this->addCSS('overflow', 'visible', 'mb-social', 'normal', $css);

				$css = $this->addCSS('z-index', '0', 'mb-social', 'normal', $css);
				$css = $this->addCSS('position', 'static', 'mb-social', 'normal', $css);
				$css = $this->addCSS('display', 'inline-block', 'mb-item', 'normal', $css);


			break;
			case 'flip':
				$css = $this->addCSS('transform', 'translateY(0)', 'mb-label', 'hover', $css);
				$css = $this->addCSS('transform', 'translateY(0)', 'mb-share-count','hover', $css);
				$css = $this->addCSS('transform', 'translateY(-100%)', 'mb-icon-wrapper', 'hover', $css);
				$css = $this->addCSS('overflow', 'hidden','mb-social', 'normal', $css);
				$css = $this->addCSS('display','inline-block', 'mb-social', 'normal', $css);

				$normcss = array(
						'transition' => 'all .2s linear',
						'height' => '100%',
						'width' => '100%',
					//	'display' => 'flex',
						'align-items' => 'center',
						'justify-content' => 'center',
						'transform' => 'translateY(100%)',
				);

				$css = $this->addCSS($normcss, false, 'mb-label', 'normal', $css);
				$css = $this->addCSS($normcss, false, 'mb-share-count', 'normal', $css);
				$css = $this->addCSS($normcss, false, 'mb-icon-wrapper', 'normal', $css);
				$css = $this->addCSS('transform', 'translateY(0)', 'mb-icon-wrapper', 'normal', $css);
				$css = $this->addCSS('position', 'absolute', 'mb-icon-wrapper', 'normal', $css);


				$hoverCSS = array(
						//				'display' => 'flex',
										'align-items' => 'center',
										'justify-content' => 'center',
										//'transform' => 'translateY(0)',
				);


				$css = $this->addCSS($hoverCSS, false, 'mb-label', 'hover', $css);
				$css = $this->addCSS($hoverCSS, false, 'mb-share-count', 'hover', $css);
				$css = $this->addCSS($hoverCSS, false, 'mb-icon-wrapper', 'hover', $css);
				$css = $this->addCSS('transform', 'translateY(-100%)', 'mb-icon-wrapper', 'hover', $css); // move up


			break;
			case 'hover';


			break;
			case 'lift':
				$line_height = intval($args['line_height']) * 1.5;

				//$this->addCSS('overflow', 'hidden', 'collection-item');
				$css = $this->addCSS('transform', 'translateY(-10px)', 'mb-icon-wrapper', 'hover', $css);
				$css = $this->addCSS('transition', 'all .2s linear', 'mb-icon-wrapper', 'normal', $css);

				$normcss = array(
					'bottom' => '-' . $line_height . 'px',
					'z-index' => 'auto',
					'background' => 'transparent',
					'position' => 'absolute',
					'line-height' => $line_height . 'px',
					'transition' => 'bottom .2s linear',
					//'z-index' => '-1',
					'width' => '100%',

				);

				$css = $this->addCSS($normcss, '', 'mb-label', 'normal', $css);
				$css = $this->addCSS($normcss, '', 'mb-share-count', 'normal', $css);

				$hovercss = array(
						'bottom' => '0px',
						'background' => 'transparent',

				);

				$css = $this->addCSS($hovercss, '', 'mb-label', 'hover', $css);
				$css = $this->addCSS($hovercss, '', 'mb-share-count', 'hover', $css);

				$css = $this->addCSS('overflow', 'hidden', 'mb-social', 'normal', $css);

			break;
			case 'none':

				$this->display['hover'] = 'icon';
				//$css = $this->addCSS('display', 'none', 'mb-label', 'hover', $css);
				//$css = $this->addCSS('display', 'none', 'mb-share-count','hover', $css);
			break;
			case 'shift':
				 $this->display = array('normal' => 'icon,count',
																'hover'  => 'icon,label,count');
				 $line_height = $args['line_height'];

					$css = $this->addCSS('overflow', 'hidden','mb-social', 'normal', $css);

					$css = $this->addCSS( array(
							'position' => 'absolute',
							'bottom' => '3px',
							'height' => $line_height,
							'z-index' => '1',
							'transition' => 'all .2s ease-in-out',
							'background' => 'transparent',
							'right' => 0,
							'left' => 0,
						),
						'',
						'mb-share-count',
						'normal',
						$css);

					$css = $this->addCSS ( array(
							'transform' => 'translateX(60px)',
							'bottom' => '0',
							'background' => 'transparent',
						),
						'',
						'mb-share-count',
						'hover',
						$css
					);

					$css = $this->addCSS ( array (
							'transform' => 'translateX(-60px)',
							'position' => 'absolute',
							'bottom' => 0,
							'transition' => 'all .2s ease-in-out',
							'background-color' => 'transparent',
							'left' => 0,
							'text-align' => 'center',
							'width' => '100%',
							'height' => $line_height,
							),
							'',
							'mb-label',
							'normal',
							$css
					);

					$css = $this->addCSS ( array (
							'transform' => 'translateX(0px)',
							'z-index' => '1',
							'bottom' => '3px',
							'background-color' => 'transparent',
							),
							'',
							'mb-label',
							'hover',
							$css
					);

					$css = $this->addCSS ('transform', 'translateY(-8px)', 'mb-icon-wrapper', 'hover', $css);
					$css = $this->addCSS ('transition', 'all .2s ease-in-out', 'mb-icon-wrapper', 'normal', $css);

			break;
			case 'stretch':
			$this->display = array('normal' => 'icon,count',
														 'hover'  => 'icon,label,count');
				 $css = $this->addStretch($css,$args);

			break;
			case 'transform':
				$scale = intval($args['scale']);
				$scale = $scale / 10;
				//$css['mb-social']['hover']['transform'] = 'scale(' . $scale . ')';
				//$css['mb-social']['hover']['transform'] = 'scale(' . $scale . ')';
			 	$css = $this->addCSS('transform', 'scale('. $scale . ')', 'mb-social','hover', $css);
			break;
		}

		return $css;
	}

	protected function addStretch($css, $args)
	{
		$css = $this->addCSS('display', 'flex', 'maxsocial', 'normal', $css);

		$orientation = (isset($args['orientation'])) ? $args['orientation'] : false;
		$is_static = (isset($args['is_static'])) ? $args['is_static'] : false;


		if ($orientation == 'vertical')
		{
			 $css = $this->addCSS('flex-direction', 'column', 'maxsocial', 'normal', $css);
		}


		$css = $this->addCSS('flex', '1.2', 'mb-item', 'hover', $css);

		$itemcss =  array(
						'flex' => 1,
						'float' => 'left',
						'height' => '100%',
						'-webkit-transition' => 'all .2s linear',
						'transition' =>  'all .2s linear',
					);
		$css = $this->addCSS($itemcss, '','mb-item', 'normal', $css);

		$linkcss = array (
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
		);

		// Changing width on horizontal items will mess the size
		if ( true === $is_static)
		{
				unset($linkcss['width']);
		}


		$css = $this->addCSS ($linkcss ,
			'',
			'mb-social', 'normal', $css);


		$css = $this->addCSS('flex', '1.2', 'mb-social', 'hover', $css);

		// the label
		$css = $this->addCSS ( array (
			'text-align' => 'center',
			'flex-grow' => 3,
			'margin' => 'auto',
			'display' => 'none',
		'opacity' => 0,
			'-webkit-transition' => 'all .4s linear',
			'transition' => 'all .4s linear',
			),
			'',
			'mb-label',
			'normal',
			$css
		);

		// label hover
		$css = $this->addCSS( array(
			'display' => 'inline-block',
			'opacity' => '1',

			),
			'',
			'mb-label',
			'hover',
			$css

		);

		$css = $this->addCSS ( array (
			'text-align' => 'center',
			'flex-grow' => 2,
			'margin' => 'auto',
			),
			'',
			'mb-icon-wrapper',
			'normal',
			$css
		);

		$css = $this->addCSS ( array (
	//		'font-size' => '16px',
			'margin' => 'auto 0',
			//'flex-grow' => '1',
			'display' => 'inline-block',
			'transform' => 'translateX(-10px)',
			),
			'',
			'mb-share-count',
			'normal',
			$css
		);

		$css = $this->addCSS('flex-grow', '1', 'mb-share-count', 'hover', $css);

		return $css;
	}

	/* Total element is counting all shares and displays some label */
	protected function addTotal($css)
	{
					// total main element
		//$this->addFlex('maxbutton-social-total');
		$styleBlock = $this->collection->getBlock('styleBlock');
		$layoutBlock = $this->collection->getBlock('layoutBlock');

		$width = $styleBlock->getValue('mbs-width');
		$height = $styleBlock->getValue('mbs-height');

	//	$this->css['maxbutton-social-total'];
		//$width = $css['mb-social']['normal']['width'];
		//$height = $css['mb-social']['normal']['height'];

	/*$width = isset($css['mbsocial']['normal']['width'])  ? $css['mbsocial']['normal']['width'] : 'auto';
	$height = isset($css['mbsocial']['normal']['height'])  ? $css['mbsocial']['normal']['height'] : 'auto';
 */
	$width =  maxUtils::strip_px($width);
	$height =  maxUtils::strip_px($height);

	// copy mb-item and put it to mb-item-total. Not going to be exactly the same.
	$itemcss = $css['mb-item'];

	$css = $this->addCSS($itemcss['normal'], '', 'mb-total-container', 'normal', $css);

	$margin = maxUtils::strip_px($layoutBlock->getValue('button_spacing'));

	if ($this->collection->orientation == 'vertical')
	{
		$width = $width . 'px';
		$height = '100%';
	}
	else { // horizontal
		$width = ($width + $margin) * 2;
		$width = $width . 'px';
		$height = $height . 'px';
	}

	// attempt to auto-custmz.
	$count_size = (isset($css['mb-share-count']['normal']['font-size'])) ? $css['mb-share-count']['normal']['font-size'] : '13';
	$count_size = maxUtils::strip_px($count_size);

	$total_count_size = $count_size * 1.5;


		$css = $this->addCSS (	array(
				 'width' => $width,
				 'height' => $height,
				 'display' => 'flex',
				 'align-items' => 'center',
				 'justify-content' => 'center',
				//'font-size' => '16px',
 				),
				'',
				'maxbutton-social-total',
				'normal',
				$css
				);


		$css = $this->addCSS ( array(
				//'float' => 'left',
				'font-size' => '20px',
				'width' => '45%',
				'text-align' => 'left',
				'height' => 'inherit',
				//'height' => 'inherit',
				'display' => 'flex',
				'align-items' => 'center',
				'justify-content' => 'center',
				),
				'',
				'mb-icon-total',
				'normal',
				$css
			);

		$css = $this->addCSS ( array (  // *sigh*
				'font-size' => '35px',
				//'height' => 'auto',
				'vertical-align' => 'top',
				),
				'',
				'mb-icon-total-icon',
				'before',
				$css
				);

		$css = $this->addCSS ( array (
				'width' => '35px',
				'height' => '35px'
			),
				'',
				'mb-icon-total-icon',
				'normal',
				$css
		);

		$css = $this->addCSS ( array (
				//'float' => 'right',
				'clear' => 'right',
				'text-align' => 'center',
	//			'width' => '45%',
				'text-transform' => 'uppercase',
				'font-size' => '13px',
				),
				'',
				'mb-label-total',
				'normal',
				$css
			);

		$css = $this->addCSS ( array (
				//'float' => 'right',
				'text-align' => 'center',
				'font-size' => $total_count_size .'px',

				),
				'',
				'mb-count-total',
				'normal',
				$css
			);


			return $css;
	}


	public function addCSS($name, $value = '', $element = 'mb-social', $pseudo = 'normal', $css = false)
	{
		if (! $css && !isset($this->css[$element][$pseudo]))
		{
				$this->css[$element][$pseudo] = array();
		}
		if ($css && !isset($css[$element][$pseudo]))
		{
				$css[$element][$pseudo] = array();
		}

		if ( is_array($name))
		{
			foreach ($name as $n => $v)
			{
				if ($css)
					$css[$element][$pseudo][$n] = $v;
				else
				{
					$this->css[$element][$pseudo][$n] = $v;
				}
			}

		}
		else
		{
			if ($css)
			{
					$css[$element][$pseudo][$name] = $value;
			}
			else
				$this->css[$element][$pseudo][$name] = $value;
		}

		if ($css)
			return $css;
	}




	/* Function that runs before the CSS output is collected from the blocks to be brought to the CSSParser. Load with env. details will allow for relevant changes */
	public function prepareCSS($args)
	{

		$default_args = array(
			'orientation' => 'horizontal',
			'is_static' => false,
			'is_post' => false,
			'is_mobile' => false,
			'is_tablet' => false,
		);

		$args = wp_parse_args($args, $default_args);

		if ($args['orientation'] == 'vertical')
		{
			$this->addCSS(array(
	/*			 'width' => $this->width . 'px',
				 'height' => $this->height  . 'px', */
		//		 'width' => '100%',
				 'flex-direction' => 'column'
				 ),
			'',
			'maxbutton-social-total'
			);

			$this->addCSS('display', 'none', 'mb-icon-total');
			$this->addCSS('width', 'auto', 'mb-label-total');
		}


	}

	public function processDisplay($css = false)
	{
		$display = $this->display;
		$active_displays = $this->display_active;

		foreach($display as $pseudo => $item)
		{
				//$normal = $display['normal'];
				$hides = array();

				if (is_array($item))  // if display list is an array
				{
						foreach($item as $i)
						{
							if (in_array($i, $active_displays))
							{
								$hides = $this->items;

								$statement = 'inline-block';

								$css = $this->addCSS('display',$statement, $this->items[$i], $pseudo, $css);
							//	$css = $this->addCSS('opacity','1', $this->items[$i], $pseudo, $css);
								unset($hides[$i]);
								break;
							}
						}
				}
				elseif (strpos($item, ',')  !== false) // if display list is comma-separated
				{
						$show_items = explode(',', $item);
						$hides = $this->items;
						foreach($show_items as $i)
						{
							unset($hides[$i]);
							$statement = 'inline-block';

							$css = $this->addCSS('display', $statement, $this->items[$i], $pseudo, $css);
							//$css = $this->addCSS('opacity', '1', $this->items[$i], $pseudo, $css);
						}
				}
				else { // if display list is just one item.
							$hides = $this->items;
							unset($hides[$item]);

							$statement = 'inline-block';


							$css = $this->addCSS('display',$statement,$this->items[$item], $pseudo, $css);
							//$css = $this->addCSS('opacity','1',$this->items[$item], $pseudo, $css);
				}

				foreach($hides as $name => $cname)
				{
					$css = $this->addCSS('display','none', $cname, $pseudo, $css);
					//$css = $this->addCSS('opacity','0', $cname, $pseudo, $css);
				}

		}

		return $css;
	}

	//  Let The style know some display item has been added.
	public function addDisplay($item)
	{
		$this->display_active[] = $item;

	}

 /* Function for changes to CSS ( eg styling exceptions ) after blocks generated CSS. Runs BEFORE the final CSS Parse run */
	public function preParse($css)
	{
			$w = MBSocial()->whistle();

			$css = $this->processDisplay($css);
			//$w->tell('display/env/has_totals', true);

			$display = $this->display;

			$active_displays = $this->display_active;
			$effect_active = $this->effect_active;

			$has_totals = $w->ask('display/env/has_totals');
			if ($has_totals)
			{
				 $css = $this->addTotal($css);
			}

			if ($effect_active)
			{
				switch($effect_active)
				{
					case "flip":

							$items = array('mb-share-count', 'mb-label');

							foreach($items as $item)
							{
								if (isset($css[$item]['hover']['display']) && $css[$item]['hover']['display'] != 'none')
								{
										$css[$item]['hover']['display'] = 'flex';
										$css[$item]['normal']['display'] = 'flex';
								}
							}

							$css['mb-icon-wrapper']['hover']['display'] = 'flex';
							$css['mb-icon-wrapper']['normal']['display'] = 'flex';
					break;
					case 'stretch':
						if ( isset( $css['mb-total-container'] ) && isset($css['mb-total-container']['normal']['flex']) )
						{
							unset($css['mb-total-container']['normal']['flex']); // remove flex on total container
						}

					break;
					case 'lift':
					case 'shift':
					case 'drop':

							$items = array('mb-share-count', 'mb-label');

							foreach($items as $item)
							{
								if (isset($css[$item]['hover']['display']) && $css[$item]['hover']['display'] != 'none')
								{
										$css[$item]['hover']['display'] = 'inline-block';
										$css[$item]['normal']['display'] = 'inline-block';
								}
							}
							$css['mb-icon-wrapper']['hover']['display'] = 'inline-block';
					//		$css['mb-icon-wrapper']['normal']['display'] = 'inline-block';


					break;
				} // switch
			} // if

			$this->display_active = array(); // just before parse, prepare for next.

			return $css;

	}

	// function to plug into when the style is set active in a collection. Can be used to hook into style specific layout options.

 public function setActive($collection)
	{
			$this->collection = $collection;
	}


} // class
