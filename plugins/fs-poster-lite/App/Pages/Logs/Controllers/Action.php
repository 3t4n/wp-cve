<?php

namespace FSPoster\App\Pages\Logs\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;

class Action
{
	public function get_logs ()
	{
		$filter_by  = Request::get( 'filter_by', 'all', 'string', [ 'all', 'error', 'ok' ] );
		$logs_page  = Request::get( 'logs_page', 1, 'int' );

		DB::DB()->query('UPDATE ' . DB::table( 'feeds' ) . ' tb1 SET is_seen = 1 WHERE is_sended = 1 AND ( ( node_type=\'account\' AND ( SELECT COUNT(0) FROM ' . DB::table( 'accounts' ) . ' tb2 WHERE tb2.id = tb1.node_id ) > 0 ) OR ( node_type <> \'account\' AND (SELECT COUNT(0) FROM ' . DB::table( 'account_nodes' ) . ' tb2 WHERE tb2.id = tb1.node_id > 0 ) ) ) AND ( is_seen IS NULL OR is_seen = 0 )' );

		return [
			'filter_by'  => $filter_by,
			'logs_page'  => $logs_page
		];
	}
}