<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Html_Grid_HC_MVC extends _HC_MVC
{
	protected $mobile = FALSE;
	protected $gutter = 0; // from 0 to 4
	protected $children = array();

	public function __toString()
	{
		return '' . $this->render();
	}

	public function set_mobile( $mobile = TRUE )
	{
		$this->mobile = $mobile;
		return $this;
	}

	function set_gutter( $gutter )
	{
		$this->gutter = $gutter;
		return $this;
	}

	function add( $child, $width, $mobile_width = 12 )
	{
		$this->children[] = array( $child, $width, $mobile_width );
		return $this;
	}

	function render()
	{
		$out = $this->app->make('/html/element')->tag('div');
		$gutter = $this->gutter;

		$out
			->add_attr('class', 'hc-clearfix')
			;
		if( $gutter ){
			$out
				->add_attr('class', 'hc-mxn' . $gutter)
				;
		}

		foreach( $this->children as $child_array ){
			list( $child, $width, $mobile_width ) = $child_array;

			$slot = $this->app->make('/html/element')->tag('div')
				;

			$css_classes = $this->_get_col_class( $width, $mobile_width, $gutter );
			foreach( $css_classes as $css_class ){
				$slot
					->add_attr('class', $css_class)
					;
			}

			$slot->add( $child );
			$out->add( $slot );
		}
		return $out;
	}

	protected function _get_col_class( $width, $mobile_width, $gutter )
	{
		$class = array();
		$mobile = $this->mobile;

		$manual = FALSE;
		$check_manual = array('%', 'em', 'px', 'rem');
		/* check if width contains %% then we need to set it manually */
		foreach( $check_manual as $check ){
			if( substr($width, -strlen($check)) == $check ){
				$manual = TRUE;
				break;
			}
		}

		$class = array();

		// if( $mobile ){
		if( $mobile_width != 12 ){
			$class[] = 'hc-xs-col';
			if( ! $manual ){
				$class[] = 'hc-xs-col-' . $mobile_width;
			}
		}

		$class[] = 'hc-col';
		if( ! $manual ){
			$class[] = 'hc-col-' . $width;
		}
		$class[] = 'hc-mb' . $gutter;
		// $class[] = 'hc-xs-mb' . $gutter;

		if( $gutter ){
			$class[] = 'hc-px' . $gutter;
		}

		return $class;
	}
}