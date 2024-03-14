<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Layout_Header_Menubar_Sidebar_Content_HC_MVC extends _HC_MVC
{
	private $content = NULL;
	private $header = NULL;
	private $menubar = NULL;
	private $sidebar = NULL;
	private $selected_sidebar = NULL;

	public function __toString()
	{
		return '' . $this->render();
	}

	public function set_content( $content )
	{
		$this->content = $content;
		return $this;
	}
	public function content()
	{
		return $this->content;
	}
	public function set_header( $header )
	{
		$this->header = $header;
		return $this;
	}
	public function header()
	{
		return $this->header;
	}
	public function set_menubar( $menubar )
	{
		$this->menubar = $menubar;
		return $this;
	}
	public function menubar()
	{
		return $this->menubar;
	}

	public function set_sidebar( $sidebar, $selected_sidebar = NULL )
	{
		$this->sidebar = $sidebar;
		$this->selected_sidebar = $selected_sidebar;
		return $this;
	}
	public function sidebar()
	{
		return $this->sidebar;
	}

	
	public function render()
	{
		$header = $this->header();
		$menubar_array = $this->menubar();
		$sidebar_array = $this->sidebar();
		$content = $this->content();

		$out = $this->app->make('/html/list')
			->set_gutter(3)
			;

		if( $header OR $menubar_array ){
			if( (! $menubar_array) OR (count($menubar_array) > 3) ){
				$row1 = $this->app->make('/html/list')
					->set_gutter(2)
					;
			}
			else {
				$row1 = $this->app->make('/html/list-inline')
					->set_gutter(2)
					;
			}

			if( $header ){
				if( ! is_object($header) ){
					$header = $this->app->make('/html/element')->tag('h1')
						->add( $header )
						->add_attr('style', 'padding: 0 0 0 0;')
						;
				}

				$row1->add( $header );

				// wordpress?
				if( defined('WPINC') && is_admin() ){
					if( ! defined('HC_WP_HEADER_END_USED') ){
						$wp_header_end = $this->app->make('/html/element')->tag('hr')
							->add_attr('class', 'wp-header-end')
							;
						define('HC_WP_HEADER_END_USED', 1);
					}
					$row1 = $this->app->make('/html/element')->tag('div')
						->add( $row1 )
						->add( $wp_header_end )
						;
				}
			}

			if( $menubar_array ){
				$menubar = $this->app->make('/html/list-inline')
					->set_gutter(1)
					;
				foreach( $menubar_array as $k => $v ){
					if( is_object($v) ){
						if( method_exists($v, 'mode') ){
							$v->mode('web');
						}
						if( method_exists($v, 'add_attr') ){
							$v
								->add_attr('class', 'hc-theme-btn-submit')
								->add_attr('class', 'hc-theme-btn-secondary')
								// ->add_attr('class', 'hc-xs-block')
								;
						}
					}
					$menubar->add($v);
				}
				$row1->add( $menubar );
			}

			$row1 = $this->app->make('/html/element')->tag('div')
				->add( $row1 )
				->add_attr('class', 'hc-py2')
				->add_attr('class', 'hc-border-bottom')
				->add_attr('class', 'hc-border-gray')
				;

			$out
				->add( $row1 )
				;
		}

		if( $sidebar_array ){
			$row2 = $this->app->make('/html/grid')
				->set_gutter(2)
				;

			$sidebar = $this->app->make('/html/list')
				->set_gutter(1)
				;
			foreach( $sidebar_array as $k => $v ){
				if( is_object($v) ){
					if( method_exists($v, 'mode') ){
						$v->mode('web');
					}
					if( method_exists($v, 'add_attr') ){
						$v
							->add_attr('class', 'hc-block')
							->add_attr('class', 'hc-theme-tab-link')
							;

						if( $this->selected_sidebar == $k ){
							$v
								->add_attr('class', 'hc-theme-tab-link-active')
								;
						}
					}
				}
				$sidebar->add($v);
			}

			$row2
				->add( $sidebar, 2, 12 )
				->add( $content, 10, 12 )
				;

			$out->add( $row2 );
		}
		else {
			$out->add( $content );
		}

		return $out;
	}
}