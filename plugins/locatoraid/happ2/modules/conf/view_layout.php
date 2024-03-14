<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Conf_View_Layout_HC_MVC extends _HC_MVC
{
	public function tabs()
	{
		$return = array();

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

	// add sorting order
		$sort = array();
		$sort_order = 100;

		$keys = array_keys($return);
		foreach( $keys as $k ){
			if( is_array($return[$k]) && isset($return[$k][2]) ){
				$this_sort_order = $return[$k][2];
				$sort[$k] = $this_sort_order;
			}
			else {
				$sort[$k] = $sort_order;
				$sort_order += 10;
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

	public function sidebar()
	{
		$return = array();
		$tabs = $this->tabs();

		reset( $tabs );

		foreach( $tabs as $tab_key => $tab ){
			if( is_array($tab) ){
				$tab_link = array_shift( $tab );
				$tab_label = array_shift( $tab );
				if( substr($tab_link, 0, 1) != '/' ){
					$tab_link = '/' . $tab_link;
				}
			}
			else {
				$tab_link = '/conf/' . $tab_key;
				$tab_label = $tab;
			}

			$link = $this->app->make('/html/ahref')
				->to( $tab_link, NULL )
				->add( $tab_label )
				;

			$return[ $tab_key ] = $link;
		}

		return $return;
	}

	public function render( $content, $current_tab = NULL )
	{
		$top_menu = $this->app->make('/layout/top-menu');
		$top_menu->set_current( 'conf' );

		$header = __('Configuration', 'locatoraid');
		$sidebar = $this->sidebar();

		// $out = $this->app->make('/layout/view/content-header-menubar')
		$out = $this->app->make('/layout/header-menubar-sidebar-content')
			->set_content( $content )
			->set_header( $header )
			->set_sidebar( $sidebar, $current_tab )
			;

		return $out;
	}
}
