<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Index_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$uri = $this->app->make('/http/uri');

		$sort = $uri->param('sort');
		$skip = $uri->param('skip');
		$search = $uri->param('search');
		$page = $uri->param('page');
		if( ! $page ){
			$page = 1;
		}

		$minPerPage = 20;
		$per_page = $uri->param('perpage');
		if( ! strlen($per_page) )
			$per_page = $minPerPage;

		$command = $this->app->make('/locations/commands/read');

		$count_args = array();
		$count_args[] = 'count';
		if( $search ){
			$count_args[] = array('search', $search);
		}
		if( $skip ){
			$count_args[] = array('id', 'NOTIN', $skip);
		}

		$total_count = $command
			->execute( $count_args )
			;

		$limit = $per_page;

		if( $total_count > $minPerPage ){
			$pager = $this->app->make('/html/pager')
				->set_total_count( $total_count )
				->set_per_page( $per_page )
				;
			if( $page > $pager->number_of_pages() ){
				$page = $pager->number_of_pages();
			}
		}

		$command_args = array();
		$command_args[] = array('with', '-all-');

		if( $per_page ){
			if( $page && $page > 1 ){
				$command_args[] = array('limit', $per_page, ($page - 1) * $per_page);
			}
			else {
				$command_args[] = array('limit', $per_page);
			}
		}

		if( $sort ){
			$command_args[] = array('sort', $sort);
		}
		if( $skip ){
			$command_args[] = array('id', 'NOTIN', $skip);
		}
		if( $search ){
			$command_args[] = array('search', $search);
		}

		$entries = $command
			->execute( $command_args )
			;

		$view = $this->app->make('/locations/index/view')
			->render($entries, $total_count, $page, $search, $per_page)
			;
		$view = $this->app->make('/locations/index/view/layout')
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