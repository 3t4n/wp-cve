<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Layout_View_Content_Header_Menubar_HC_MVC extends _HC_MVC
{
	private $content = NULL;
	private $header = NULL;
	private $menubar = NULL;

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

	public function render()
	{
		$header = (string) $this->header();

		$menubar = $this->menubar();
		$submenu = FALSE;

		if( is_object($menubar) && method_exists($menubar, 'children') && ($menubar_items = $menubar->children()) ){
			$submenu = TRUE;
		}

		if( is_object($menubar) && method_exists($menubar, 'render') ){
			$menubar = $menubar->render();
			// _print_r( $menubar );
			// echo 'children = ' . count($menubar->children());
			
			if( is_object($menubar) && method_exists($menubar, 'children') && ($menubar_items = $menubar->children()) ){
				$submenu = TRUE;
			}
		}
		elseif( is_array($menubar) && $menubar ){
			$menubar_items = $menubar;
			$submenu = TRUE;
		}

		if( strlen($header) OR $submenu ){
			$header_responsive = NULL;

			if( $submenu ){
				$submenu_content_responsive = $this->app->make('/html/list')
					->set_gutter(1)
					;

				foreach( $menubar_items as $k => $v ){
					if( is_object($v) ){
						if( method_exists($v, 'add_attr') ){
							$v
								->add_attr('class', 'hc-theme-btn-submit')
								->add_attr('class', 'hc-theme-btn-secondary')
								->add_attr('class', 'hc-xs-block')
								;
						}

						if( method_exists($v, 'mode') ){
							$v->mode('web');
						}
					}

					$v = '' . $v;
					$submenu_content_responsive
						->add( $v )
						;
				}

				$submenu_fullscreen = $this->app->make('/html/list-inline')
					->set_gutter(2)
					;
				reset( $menubar_items );
				foreach( $menubar_items as $k => $v ){
					$submenu_fullscreen
						->add( $v )
						;
				}
				$submenu_fullscreen = $this->app->make('/html/element')->tag('div')
					->add( $submenu_fullscreen )
					->add_attr('class', 'hc-mt2')
					;

				$icon_menu_responsive = $this->app->make('/html/icon')->icon('menu');
				$icon_menu_responsive = $this->app->make('/html/element')->tag('span')
					->add_attr('class', 'hc-p1')
					->add_attr('class', 'hc-border')
					->add_attr('class', 'hc-border-gray')
					->add_attr('class', 'hc-rounded')
					->add( $icon_menu_responsive )
					;

				$header_responsive = $this->app->make('/html/list-inline')
					->set_gutter(2)
					->set_mobile(TRUE)
					->add( $icon_menu_responsive )
					->add( $header )
					;

				$header_responsive = $this->app->make('/html/element')->tag('h1')
					->add( $header_responsive )
					->add_attr('style', 'line-height: 1.5em;')
					->add_attr('class', 'hc-py2')
					->add_attr('class', 'hc-nowrap')
					;

				$header_responsive = $this->app->make('/html/collapse')
					->set_title( $header_responsive )
					->set_content( $submenu_content_responsive )
					;

				$header_responsive = $this->app->make('/html/element')->tag('div')
					->add( $header_responsive )
					->add_attr('class', 'hc-lg-hide')
					;
			}

			if( strlen($header) ){
				$header = $this->app->make('/html/element')->tag('h1')
					->add( $header )
					->add_attr('style', 'padding: 0 0 0 0;')
					;
			}

			if( $submenu ){
				if( count($menubar_items) < 4 ){
					if( strlen($header) ){
						$header = $this->app->make('/html/list-inline')
							->set_gutter(3)
							->add( $header )
							->add( $submenu_fullscreen )
							;
					}
					else {
						$header = $submenu_fullscreen;
					}
				}
				else {
					$header = $this->app->make('/html/list')
						->set_gutter(1)
						->add( $header )
						->add( $submenu_fullscreen )
						;
				}

				$header = $this->app->make('/html/element')->tag('div')
					->add( $header )
					->add_attr('class', 'hc-xs-hide')
					;
			}

			$header = $this->app->make('/html/element')->tag('div')
				->add( $header )
				->add_attr('class', 'hc-mt1')
				->add_attr('class', 'hc-pb1')  
				;

			if( $header_responsive ){
				$header
					->add( $header_responsive )
					;
			}
		}

		$content = $this->app->make('/html/element')->tag('div')
			->add( $this->content() )
			->add_attr('class', 'hc-py2')
			;

		$out = $this->app->make('/html/element')->tag(NULL)
			;

		if( $header ){
			// wordpress?
			if( defined('WPINC') && is_admin() ){
				if( ! defined('HC_WP_HEADER_END_USED') ){
					$wp_header_end = $this->app->make('/html/element')->tag('hr')
						->add_attr('class', 'wp-header-end')
						;
					$header
						->add( $wp_header_end )
						;
					define('HC_WP_HEADER_END_USED', 1);
				}
			}
			$out->add( $header );
		}
		$out->add( $content );

		$out = $this->app
			->after( $this, $out )
			;

		return $out;
	}
}