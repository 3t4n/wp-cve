<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Searchlog_Export_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$uri = $this->app->make('/http/uri');
		$search = $uri->param('search');

		$command = $this->app->make('/commands/read')
			->set_table('searchlog')
			;

		$command_args = array();
		$command_args[] = array('sort', 'action_time', 'desc');

		$entries = $command
			->execute( $command_args )
			;

		$settings = $this->app->make('/app/settings');
		$separator = $settings->get('core:csv_separator');
		if( ! strlen($separator) ){
			$separator = ',';
		}

		$data = array();
		$headerFields = array( 'ip_address', 'search_text', 'search_time' );

		$header = hc2_build_csv( $headerFields, $separator );
		$data[] = $header;

		foreach( $entries as $e ){
			$e2 = array();
			$e2['ip_address'] = $e['ip_address'];
			$e2['search_text'] = $e['search_text'];
			$e2['search_time'] = date( 'j M Y g:ia', $e['action_time'] );
			$row = hc2_build_csv( $e2, $separator );
			$data[] = $row;
		}

// $ret = '';
// foreach( $data as $line ){
	// $ret .= $line . '<br>';
// }
// return $ret;

		$out = $this->app->make('/http/lib/download');

		$file_name = 'searchlog-export';
		$file_name .= '-' . date('Y-m-d_H-i') . '.csv';

		$data = join("\n", $data);
		$out->download( $file_name, $data );
		exit;


_print_r( $entries );
exit;


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