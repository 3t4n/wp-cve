<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Html_List_HC_MVC extends _HC_MVC
{
	protected $children = array();
	protected $gutter = 0; // from 0 to 3

	public function __toString()
	{
		return '' . $this->render();
	}

	public function add( $child ){
		$this->children[] = $child;
		return $this;
	}

	public function set_gutter( $gutter )
	{
		$this->gutter = $gutter;
		return $this;
	}

	function render()
	{
		$gutter = $this->gutter;

		$out = $this->app->make('/html/element')->tag('div')
			// ->add_attr('class', 'hc-border')
			;

		$ii = 0;
		foreach( $this->children as $child ){
			$child_out = $this->app->make('/html/element')->tag('div')
				->add( $child )
				->add_attr('class', 'hc-block')
				// ->add_attr('class', 'hc-border')
				;

			if( $gutter ){
				if( $ii ){
					$child_out
						->add_attr('class', 'hc-mt' . $gutter)
						;
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