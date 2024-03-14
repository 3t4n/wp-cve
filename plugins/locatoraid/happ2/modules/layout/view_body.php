<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Layout_View_Body_HC_MVC extends _HC_MVC
{
	private $content = NULL;

	public function set_content( $content )
	{
		$this->content = $content;
		return $this;
	}

	public function content()
	{
		$return = $this->content;

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}

	public function top_header()
	{
		$return = array();

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}

	public function render()
	{
		$nts = $this->app->make('/html/element')->tag('div')
			->add_attr('class', 'hc-app-container')
			->add_attr('id', 'hc-app')
			;

		$content = $this->content();
		// if( is_object($content) && method_exists($content, 'render') ){
			// $content = $content->render();
		// }

		$top_header = $this->top_header();
		if( $top_header ){
			$top_header_view = $this->app->make('/html/list')
				->set_gutter(1)
				;
			foreach( $top_header as $h ){
				$top_header_view
					->add( $h )
					;
			}

			$nts
				->add( $top_header_view )
				;
		}

		$nts
			->add( $content )
			;

		$nts = $this->app->make('/html/element')->tag('div')
			->add( $nts )
			;

		if( defined('WPINC') && is_admin() ){
			$nts
				->add_attr('class', 'wrap')
				;
		}

		$out = $this->app->make('/html/element')->tag(NULL);

		$out->add( $nts );
		if( isset($js_footer) ){
			$out->add( $js_footer );
		}
		if( isset($theme_footer) ){
			$out->add( $theme_footer );
		}

		$out = $this->app
			->after( $this, $out )
			;

		return $out;
	}
}