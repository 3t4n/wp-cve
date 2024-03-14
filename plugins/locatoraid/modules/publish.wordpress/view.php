<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Publish_Wordpress_View_LC_HC_MVC extends _HC_MVC
{
	public function render()
	{
		ob_start();
		require( dirname(__FILE__) . '/view.html.php' );
		$out = ob_get_contents();
		ob_end_clean();

		$pages = array();

		$pageIds = hc2_wp_get_id_by_shortcode('locatoraid');
		foreach( $pageIds as $pid ){
			$link = get_permalink( $pid );
			$label = get_the_title( $pid );
			if( (null === $label) OR (! strlen($label)) ){
				$label = $link;
			}
			$page = $this->app->make('/html/ahref')
				->to( $link )
				->add_attr('target', '_blank')
				->add( $label )
				;
			$pages[] = $page;
		}

		$pagesView = $this->app->make('/html/list')
			->set_gutter(2)
			;
		$pagesView
			->add( $this->app->make('/html/element')->tag('h2')->add( __('Pages with shortcode', 'locatoraid') ) )
			;

		$addNewLink = $this->app->make('/html/ahref')
			->to( admin_url('post-new.php') )
			->add( __('Add New', 'locatoraid') )
			->add_attr('class', 'hc-theme-btn-submit')
			->add_attr('class', 'hc-theme-btn-secondary')
			->add_attr('class', 'hc-xs-block')
			;

		if( $pages ){
			foreach( $pages as $p ){
				$pagesView->add( $p );
			}
		}
		else {
			$pagesView
				->add( __('None', 'locatoraid') )
				;
		}
		$pagesView->add( $addNewLink );

		$pages2View = $this->app->make('/html/list')
			->set_gutter(2)
			;
		$pages2View
			->add( $this->app->make('/html/element')->tag('h2')->add( __('Pages with block', 'locatoraid') ) )
			;

		$pages2 = array();
		$pageIds2 = hc2_wp_get_id_by_block('locatoraid/locatoraid-map');
		foreach( $pageIds2 as $pid ){
			$link = get_permalink( $pid );
			$label = get_the_title( $pid );
			if( (null === $label) OR (! strlen($label)) ){
				$label = $link;
			}
			$page = $this->app->make('/html/ahref')
				->to( $link )
				->add_attr('target', '_blank')
				->add( $label )
				;
			$pages2[] = $page;
		}

		if( $pages2 ){
			foreach( $pages2 as $p ){
				$pages2View->add( $p );
			}
		}
		else {
			$pages2View
				->add( __('None', 'locatoraid') )
				;
		}
		$pages2View->add( $addNewLink );

		$allPagesView = $this->app->make('/html/list')
			->set_gutter(2)
			->add( $pagesView )
			->add( $pages2View )
			;

		$out = $this->app->make('/html/grid')
			->add( $out, 8, 12 )
			->add( $allPagesView, 4, 12 )
			->set_gutter( 2 )
			;

		return $out;
	}
}