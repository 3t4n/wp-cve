<?php

namespace FSPoster\App\Pages\Logs\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;

trait Ajax
{
	public function report1_data ()
	{
		$type    = Request::post( 'type', '', 'string' );
		$user_id = ( int ) get_current_user_id();

		if ( ! in_array( $type, [
			'dayly',
			'monthly',
			'yearly'
		] ) )
		{
			exit();
		}

		$query = [
			'dayly'   => "SELECT CAST(send_time AS DATE) AS date , COUNT(0) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE  ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.id=tb1.node_id )>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id )) ) AND is_sended=1 GROUP BY CAST(send_time AS DATE)",
			'monthly' => "SELECT CONCAT(YEAR(send_time), '-', MONTH(send_time) , '-01') AS date , COUNT(0) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.id=tb1.node_id)>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id )) ) AND is_sended=1 AND send_time > ADDDATE(now(),INTERVAL -1 YEAR) GROUP BY YEAR(send_time), MONTH(send_time)",
			'yearly'  => "SELECT CONCAT(YEAR(send_time), '-01-01') AS date , COUNT(0) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.id=tb1.node_id)>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id)) ) AND is_sended=1 GROUP BY YEAR(send_time)"
		];

		$dateFormat = [
			'dayly'   => 'Y-m-d',
			'monthly' => 'Y M',
			'yearly'  => 'Y',
		];

		$dataSQL = DB::DB()->get_results( $query[ $type ], ARRAY_A );

		$labels = [];
		$datas  = [];
		foreach ( $dataSQL as $dInf )
		{
			$datas[]  = $dInf[ 'c' ];
			$labels[] = Date::format( $dateFormat[ $type ], $dInf[ 'date' ] );
		}

		Helper::response( TRUE, [
			'data'   => $datas,
			'labels' => $labels
		] );
	}

	public function report2_data ()
	{
		$type    = Request::post( 'type', '', 'string' );
		$user_id = ( int ) get_current_user_id();

		if ( ! in_array( $type, [
			'dayly',
			'monthly',
			'yearly'
		] ) )
		{
			exit();
		}

		$query = [
			'dayly'   => "SELECT CAST(send_time AS DATE) AS date , SUM(visit_count) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.id=tb1.node_id)>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id)) ) AND is_sended=1 GROUP BY CAST(send_time AS DATE)",
			'monthly' => "SELECT CONCAT(YEAR(send_time), '-', MONTH(send_time) , '-01') AS date , SUM(visit_count) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.id=tb1.node_id)>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id)) ) AND send_time > ADDDATE(now(),INTERVAL -1 YEAR) AND is_sended=1 GROUP BY YEAR(send_time), MONTH(send_time)",
			'yearly'  => "SELECT CONCAT(YEAR(send_time), '-01-01') AS date , SUM(visit_count) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.id=tb1.node_id)>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id)) ) AND is_sended=1 GROUP BY YEAR(send_time)"
		];

		$dateFormat = [
			'dayly'   => 'Y-m-d',
			'monthly' => 'Y M',
			'yearly'  => 'Y',
		];

		$dataSQL = DB::DB()->get_results( $query[ $type ], ARRAY_A );

		$labels = [];
		$datas  = [];
		foreach ( $dataSQL as $dInf )
		{
			$datas[]  = $dInf[ 'c' ];
			$labels[] = Date::format( $dateFormat[ $type ], $dInf[ 'date' ] );
		}

		Helper::response( TRUE, [
			'data'   => $datas,
			'labels' => $labels
		] );
	}

	public function report3_data ()
	{
		$page           = Request::post( 'page', '1', 'num' );
		$rows_count     = Request::post( 'rows_count', '4', 'int', [ '4', '8', '15' ] );
		$filter_results = Request::post( 'filter_results', 'all', 'string', [ 'all', 'error', 'ok' ] );

		if ( ! ( $page > 0 ) )
		{
			Helper::response( FALSE );
		}

		$query_add = '';

		if ( $filter_results === 'error' || $filter_results === 'ok' )
		{
			$query_add .= ' AND status = "' . $filter_results . '"';
		}

		$allCount = DB::DB()->get_row( "SELECT COUNT(0) AS c FROM " . DB::table( 'feeds' ) . " tb1 WHERE is_sended=1 AND ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.id=tb1.node_id )>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id )) ) " . $query_add, ARRAY_A );
		$pages    = ceil( $allCount[ 'c' ] / $rows_count );

		Helper::setOption( 'logs_rows_count_' . get_current_user_id(), $rows_count, TRUE );

		$offset     = ( $page - 1 ) * $rows_count;
		$getData    = DB::DB()->get_results( "SELECT * FROM " . DB::table( 'feeds' ) . " tb1 WHERE is_sended=1 AND ( (node_type='account' AND (SELECT COUNT(0) FROM " . DB::table( 'accounts' ) . " tb2 WHERE tb2.id=tb1.node_id )>0) OR (node_type<>'account' AND (SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " tb2 WHERE tb2.id=tb1.node_id )) ) " . $query_add . " ORDER BY send_time DESC LIMIT $offset , $rows_count", ARRAY_A );
		$resultData = [];

		foreach ( $getData as $feedInf )
		{
			$postInf        = get_post( $feedInf[ 'post_id' ] );
			$node_infoTable = $feedInf[ 'node_type' ] === 'account' ? 'accounts' : 'account_nodes';
			$node_info      = DB::fetch( $node_infoTable, $feedInf[ 'node_id' ] );

			if ( $node_info && $feedInf[ 'node_type' ] === 'account' )
			{
				$node_info[ 'node_type' ] = 'account';
			}

			if ( $feedInf[ 'driver' ] === 'wordpress' )
			{
				$feedInf[ 'node_type' ] = 'website';
			}

			$insights = [
				'like'     => 0,
				'details'  => '',
				'comments' => 0,
				'shares'   => 0
			];

			if ( $feedInf[ 'driver' ] === 'fb' )
			{
				$icon = 'fab fa-facebook';
			}
			else if ( $feedInf[ 'driver' ] === 'vk' )
			{
				$icon = 'fab fa-vk';
			}
			else if ( $feedInf[ 'driver' ] === 'instagram' )
			{
				$icon = 'fab fa-instagram';
			}
			else if ( $feedInf[ 'driver' ] === 'linkedin' )
			{
				$icon = 'fab fa-linkedin';
			}
			else if ( $feedInf[ 'driver' ] === 'pinterest' )
			{
				$icon = 'fab fa-pinterest';
			}
			else if ( $feedInf[ 'driver' ] === 'reddit' )
			{
				$icon = 'fab fa-reddit';
			}
			else if ( $feedInf[ 'driver' ] === 'ok' )
			{
				$icon = 'fab fa-odnoklassniki';
			}
			else if ( $feedInf[ 'driver' ] === 'tumblr' )
			{
				$icon = 'fab fa-tumblr';
			}
			else if ( $feedInf[ 'driver' ] === 'wordpress' )
			{
				$icon = 'fab fa-wordpress';
			}
			else if ( $feedInf[ 'driver' ] === 'google_b' )
			{
				$icon = 'fab fa-google';
			}
			else if ( $feedInf[ 'driver' ] === 'blogger' )
			{
				$icon = 'fab fa-blogger';
			}
			else if ( $feedInf[ 'driver' ] === 'telegram' )
			{
				$icon = 'fab fa-telegram';
			}
			else if ( $feedInf[ 'driver' ] === 'medium' )
			{
				$icon = 'fab fa-medium';
			}
			else if ( $feedInf[ 'driver' ] === 'plurk' )
			{
				$icon = 'fas fa-parking';
			}

			if ( $feedInf[ 'driver' ] === 'google_b' )
			{
				$username = $node_info[ 'node_id' ];
			}
			else if ( $feedInf[ 'driver' ] === 'blogger' )
			{
				$username = $feedInf[ 'driver_post_id2' ];
			}
			else if ( $feedInf[ 'driver' ] === 'wordpress' )
			{
				$username = isset( $node_info2[ 'options' ] ) ? $node_info2[ 'options' ] : '';
			}
			else
			{
				$username = isset( $node_info[ 'screen_name' ] ) ? $node_info[ 'screen_name' ] : ( isset( $node_info[ 'username' ] ) ? $node_info[ 'username' ] : '-' );
			}

			$resultData[] = [
				'id'           => $feedInf[ 'id' ],
				'name'         => $node_info ? htmlspecialchars( $node_info[ 'name' ] ) : esc_html__( 'Account deleted', 'fs-poster' ),
				'post_id'      => htmlspecialchars( $feedInf[ 'driver_post_id' ] ),
				'post_title'   => htmlspecialchars( isset( $postInf->post_title ) ? $postInf->post_title : 'Deleted' ),
				'cover'        => Helper::profilePic( $node_info ),
				'profile_link' => Helper::profileLink( $node_info ),
				'is_sended'    => $feedInf[ 'is_sended' ],
				'post_link'    => Helper::postLink( $feedInf[ 'driver_post_id' ], $feedInf[ 'driver' ] . ( $feedInf[ 'driver' ] === 'instagram' ? $feedInf[ 'feed_type' ] : '' ), $username ),
				'status'       => $feedInf[ 'status' ],
				'error_msg'    => $feedInf[ 'error_msg' ],
				'hits'         => $feedInf[ 'visit_count' ],
				'driver'       => $feedInf[ 'driver' ],
				'icon'         => $icon,
				'insights'     => $insights,
				'node_type'    => $feedInf[ 'node_type' ],
				'feed_type'    => ucfirst( (string) $feedInf[ 'feed_type' ] ),
				'date'         => Date::dateTimeSQL( $feedInf[ 'send_time' ] ),
				'wp_post_id'   => $feedInf[ 'post_id' ]
			];
		}

		if ( ! ( $pages > 0 ) )
		{
			$pages = 1;
		}

		$show_pages = [ 1, $page, $pages ];

		if ( ( $page - 3 ) >= 1 )
		{
			for ( $i = $page; $i >= $page - 3; $i-- )
			{
				$show_pages[] = $i;
			}
		}
		else if ( ( $page - 2 ) >= 1 )
		{
			for ( $i = $page; $i >= $page - 2; $i-- )
			{
				$show_pages[] = $i;
			}
		}
		else if ( ( $page - 1 ) >= 1 )
		{
			for ( $i = $page; $i >= $page - 1; $i-- )
			{
				$show_pages[] = $i;
			}
		}

		if ( ( $page + 3 ) <= $pages )
		{
			for ( $i = $page; $i <= $page + 3; $i++ )
			{
				$show_pages[] = $i;
			}
		}
		else if ( ( $page + 2 ) <= $pages )
		{
			for ( $i = $page; $i <= $page + 2; $i++ )
			{
				$show_pages[] = $i;
			}
		}
		else if ( ( $page + 1 ) <= $pages )
		{
			for ( $i = $page; $i <= $page + 1; $i++ )
			{
				$show_pages[] = $i;
			}
		}

		$show_pages = array_unique( $show_pages );
		sort( $show_pages );

		Helper::response( TRUE, [
			'data'  => $resultData,
			'pages' => [
				'page_number'  => $show_pages,
				'current_page' => $page,
				'count'        => $pages
			],
			'total' => $allCount[ 'c' ] ? $allCount[ 'c' ] : 0
		] );
	}

	public function fs_clear_logs ()
	{
		DB::DB()->query( "DELETE FROM " . DB::table( 'feeds' ) . ' WHERE (is_sended=1 OR (send_time+INTERVAL 1 DAY)<NOW()) AND ( (node_type=\'account\' AND (SELECT COUNT(0) FROM ' . DB::table( 'accounts' ) . ' tb2 WHERE tb2.id=' . DB::table( 'feeds' ) . '.node_id)>0) OR (node_type<>\'account\' AND (SELECT COUNT(0) FROM ' . DB::table( 'account_nodes' ) . ' tb2 WHERE tb2.id=' . DB::table( 'feeds' ) . '.node_id)) )' );

		Helper::response( TRUE );
	}
}
