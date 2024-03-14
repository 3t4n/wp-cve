<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Html_List_Inline_HC_MVC extends _HC_MVC
{
	protected $children = array();
	protected $mobile = FALSE;
	protected $gutter = 2; // from 0 to 3

	public function __toString()
	{
		return '' . $this->render();
	}

	public function add( $child ){
		$this->children[] = $child;
		return $this;
	}

	public function set_mobile( $mobile = TRUE )
	{
		$this->mobile = $mobile;
		return $this;
	}

	public function set_gutter( $gutter )
	{
		$this->gutter = $gutter;
		return $this;
	}

	public function render()
	{
		$mobile = $this->mobile;
		$gutter = $this->gutter;

		$out = $this->app->make('/html/element')->tag('div')
			// ->add_attr('class', 'hc-border')
			;

		if( $mobile ){
			$out
				->add_attr('class', 'hc-nowrap')
				;
		}

		$ii = 0;
		$total_count = count( $this->children );
		foreach( $this->children as $child ){
			$child_out = $this->app->make('/html/element')->tag('div')
				->add( $child )
				->add_attr('class', 'hc-valign-middle')
				;

			if( $mobile ){
				$child_out
					->add_attr('class', 'hc-inline-block')
					;
			}
			else {
				$child_out
					->add_attr('class', 'hc-lg-inline-block')
					;
			}

			if( $gutter ){
				if( $ii < ($total_count - 1) ){
					$child_out
						->add_attr('class', 'hc-mr' . $gutter)
						;
					if( ! $mobile ){
						$child_out
							->add_attr('class', 'hc-xs-mr0')
							->add_attr('class', 'hc-xs-mb' . $gutter)
							;
					}
				}
			}

			$out
				->add( $child_out )
				;
			$ii++;
		}

		return $out;
	}
}