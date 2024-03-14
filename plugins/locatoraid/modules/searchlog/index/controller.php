<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Searchlog_Index_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute()
	{
	// delete old
		$now = time();
		$period = $this->app->make('/app/settings')->get('searchlog:period');
		if( ! $period ){
			$period = 2*7*24*60*60;
		}
		$threshold = $now - $period;

		$q = $this->app->db->query_builder();
		$q->where( 'action_time <', $threshold );
		$sql = $q->get_compiled_delete( 'searchlog' );
		$this->app->db->query( $sql );


		$uri = $this->app->make('/http/uri');

		$search = $uri->param('search');
		$page = $uri->param('page');
		if( ! $page ){
			$page = 1;
		}

		$per_page = 20;

		$command = $this->app->make('/commands/read')
			->set_table('searchlog')
			;

		$count_args = array();
		$count_args[] = 'count';
		if( $search ){
			$count_args[] = array('search', $search);
		}

		$total_count = $command
			->execute( $count_args )
			;

		$limit = $per_page;

		if( $total_count > $per_page ){
			$pager = $this->app->make('/html/pager')
				->set_total_count( $total_count )
				->set_per_page( $per_page )
				;
			if( $page > $pager->number_of_pages() ){
				$page = $pager->number_of_pages();
			}
		}

		$command_args = array();

		if( $page && $page > 1 ){
			$command_args[] = array('limit', $per_page, ($page - 1) * $per_page);
		}
		else {
			$command_args[] = array('limit', $per_page);
		}

		$command_args[] = array('sort', 'action_time', 'desc');

		if( $search ){
			$command_args[] = array('search', $search);
		}

		$entries = $command
			->execute( $command_args )
			;

		$view = $this->app->make('/searchlog/index/view')
			->render($entries, $total_count, $page, $search, $per_page)
			;
		$view = $this->app->make('/searchlog/index/view/layout')
			->render($view)
			;
		$view = $this->app->make('/layout/view/body')
			->set_content($view)
			;
		return $this->app->make('/http/view/response')
			->set_view($view)
			;
	}
}