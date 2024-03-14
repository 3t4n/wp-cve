<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Layout_Top_Menu_HC_MVC extends _HC_MVC
{
	protected $current;

	public function single_instance()
	{
	}

	public function options()
	{
		$return = array();

		$return = $this->app
			->after( $this, $return )
			;

	// add sorting order
		$sort = array();
		$sort_order = 1;

		$keys = array_keys($return);
		foreach( $keys as $k ){
			if( is_array($return[$k]) ){
				$this_sort_order = $return[$k][1];
				$return[$k] = $return[$k][0];
				$sort[$k] = $this_sort_order;
			}
			else {
				$sort[$k] = $sort_order;
				$sort_order++;
			}
		}

	// now sort
		asort($sort);

		$final_return = array();
		foreach( array_keys($sort) as $k ){
			$final_return[ $k ] = $return[ $k ];
		}

		return $final_return;
	}

	public function set_current( $current )
	{	
		$this->current = $current;
		return $this;
	}

	public function current()
	{
		return $this->current;
	}

	protected function render_small()
	{
		$children = $this->options();

		$uri = $this->app->make('/http/uri');
		$content = $this->app->make('/html/list');

		foreach( $children as $child_o ){
			if( ! $child_o ){
				continue;
			}

			if( is_object($child_o) ){
				$child = clone $child_o;
			}
			else {
				$child = $child_o;
			}

			if( is_object($child) && method_exists($child, 'add_attr') ){
				$child
					->add_attr('class', 'hc-btn')
					->add_attr('class', 'hc-block')
					->add_attr('class', 'hc-px3')
					->add_attr('class', 'hc-py2')
					->add_attr('class', 'hc-border-bottom', 'hc-border-gray')
					;

				if( method_exists($child, 'href') && ($href = $child->href()) ){
					$parsed_url = $uri->parse_url( $href );
					$this_slug = $parsed_url['slug'];

				// active
					if( 
						( $this_slug == $this->current )
						OR
						(
							( substr($this->current, 0, strlen($this_slug)) == $this_slug ) &&
							( substr($this->current, strlen($this_slug), 1) == '/' )
						)
					){
						$child
							->add_attr('class', 'hc-bg-black')
							->add_attr('class', 'hc-silver')
							;
					}
				}
			}
			$content->add( 
				$child
				);
		}

		$title = $this->app->make('/html/element')->tag('span')
			->add_attr('class', 'hc-block')

			->add_attr('class', 'hc-px2')
			->add_attr('class', 'hc-py3')
			->add_attr('class', 'hc-align-center')


			->add_attr('class', 'hc-rounded')
			->add_attr('class', 'hc-bg-darkgray')
			->add_attr('class', 'hc-silver')

			->add( __('Menu', 'locatoraid') )
			;

		$out = $this->app->make('/html/collapse')
			->set_title( $title )
			->set_content( $content )
			;

		return $out;
	}

	public function render()
	{
		$out = $this->app->make('/html/element')->tag('div')
			->add_attr('class', 'hc-mb2')
			->add_attr('class', 'hc-rounded')
			->add_attr('class', 'hc-bg-darkgray')
			->add_attr('class', 'hc-silver')
			;

		$children = $this->options();

		$uri = $this->app->make('/http/uri');

		foreach( $children as $child_o ){
			if( ! $child_o ){
				continue;
			}

			if( is_object($child_o) ){
				$child = clone $child_o;
			}
			else {
				$child = $child_o;
			}

			if( is_object($child) && method_exists($child, 'add_attr') ){
				$child
					->add_attr('class', 'hc-btn')
					->add_attr('class', 'hc-px3')
					->add_attr('class', 'hc-py3')
					->add_attr('class', 'hc-mr2')
					;

				if( method_exists($child, 'href') && ($href = $child->href()) ){
					$parsed_url = $uri->parse_url( $href );
					$this_slug = $parsed_url['slug'];

				// active
					if( 
						( $this_slug == $this->current )
						OR
						(
							( substr($this->current, 0, strlen($this_slug)) == $this_slug ) &&
							( substr($this->current, strlen($this_slug), 1) == '/' )
						)
					){
						$child
							->add_attr('class', 'hc-bg-black')
							->add_attr('class', 'hc-silver')
							;
					}
				}
			}

			$out->add( 
				$child
				);
		}

	// xs version
		$xs_out = $this->render_small();
		$xs_out = $this->app->make('/html/element')->tag('div')
			->add_attr('class', 'hc-lg-hide')
			->add( $xs_out )
			;

		$out
			->add_attr('class', 'hc-xs-hide')
			;

		$out = $this->app->make('/html/list')
			->set_gutter(0)
			->add( $out )
			->add( $xs_out )
			;

		return $out;
	}
}