<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Searchlog_Index_View_LC_HC_MVC extends _HC_MVC
{
	public function render( $entries, $total_count, $page = 1, $search = '', $per_page = 5 )
	{
		$header = $this->header();

		$rows = array();
		reset( $entries );
		foreach( $entries as $e ){
			$rows[ $e['id'] ] = $this->row( $e );
		}

		$out = $this->app->make('/html/list')
			->set_gutter(1)
			;

		$submenu = $this->app->make('/html/list-inline')
			->set_gutter(2)
			;

		if( $total_count > $per_page ){
			$pager = $this->app->make('/html/pager')
				->set_total_count( $total_count )
				->set_current_page( $page )
				->set_per_page($per_page)
				;

			$submenu
				->add( $pager )
				;
		}

		$out
			->add( $submenu )
			;

		$time_view = date( 'j M Y g:ia' );
		$out->add( $time_view );

		if( $rows ){
			$table = $this->app->make('/html/table-responsive')
				->set_no_footer(false)
				->set_header($header)
				->set_rows($rows)
				;

			$table
				->set_width( 'search', 8 )
				->set_width( 'time', 4 )
				;

			$table = $this->app->make('/html/element')->tag('div')
				->add( $table )
				->add_attr('class', 'hc-border')
				;

			$out
				->add( $table )
				;
		}
		elseif( $search ){
			$msg = __('No Matches', 'locatoraid');
			$out
				->add( $msg )
				;
		}

		return $out;
	}

	public function header()
	{
		$return = array(
			'search' 	=> __('Search', 'locatoraid'),
			'time' 		=> __('Time', 'locatoraid'),
			);

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}

	public function row( $e )
	{
		$return = array();
		if( ! $e ){
			return $return;
		}

		$search_view = $e['search_text'];
		$time = $e['action_time'];
		$time_view = date( 'j M Y g:ia', $time );

		$return['search'] = $search_view;
		$return['time'] = $time_view;

		$return = $this->app
			->after( array($this, __FUNCTION__), $return, $e )
			;

		return $return;
	}
}
